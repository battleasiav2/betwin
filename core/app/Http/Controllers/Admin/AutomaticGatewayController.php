<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Lib\RequiredConfig;

class AutomaticGatewayController extends Controller {
    public function index() {
        $pageTitle = 'Automatic Gateways';
        $gateways  = Gateway::automatic()->with('currencies')->get();
        return view('admin.gateways.automatic.list', compact('pageTitle', 'gateways'));
    }

    public function edit($alias) {
        $gateway   = $this->findAutomaticGateway($alias);
        $pageTitle = 'Update Gateway';

        $supportedCurrencies = $this->supportedCurrencyMap($gateway)->except($gateway->currencies->pluck('currency'));
        $parameters          = $this->normalizeGatewayParameters($gateway);
        $globalParameters    = null;
        $hasCurrencies       = false;
        $currencyIndex       = 1;

        if ($gateway->currencies->count()) {
            $globalParameters = json_decode($gateway->currencies->first()->gateway_parameter);
            $hasCurrencies    = true;
        }

        return view('admin.gateways.automatic.edit', compact('pageTitle', 'gateway', 'supportedCurrencies', 'parameters', 'hasCurrencies', 'currencyIndex', 'globalParameters'));
    }

    public function update(Request $request, $code) {

        $gateway = Gateway::where('code', $code)->firstOrFail();
        $this->gatewayValidator($request)->validate();
        $this->gatewayCurrencyValidator($request, $gateway)->validate();

        $parameters = $this->normalizeGatewayParameters($gateway);

        foreach ($parameters->where('global', true) as $key => $pram) {
            $parameters[$key]->value = $request->global[$key];
        }

        $filename = $gateway->image;
        if ($request->hasFile('image')) {
            try {
                $filename = fileUploader($request->image, getFilePath('gateway'), old: $filename);
            } catch (\Exception $exp) {
                $notify[] = ['errors', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $gateway->alias              = $request->alias;
        $gateway->gateway_parameters = json_encode($parameters);
        $gateway->image              = $filename;
        $gateway->save();

        if ($request->has('currency')) {

            $gateway->currencies()->delete();

            foreach ($request->currency as $key => $currency) {
                $param = [];
                foreach ($parameters->where('global', true) as $pkey => $pram) {
                    $param[$pkey] = $pram->value;
                }

                foreach ($parameters->where('global', false) as $paramKey => $paramValue) {
                    $param[$paramKey] = $currency['param'][$paramKey];
                }

                $gatewayCurrency                    = new GatewayCurrency();
                $gatewayCurrency->name              = $currency['name'];
                $gatewayCurrency->gateway_alias     = $gateway->alias;
                $gatewayCurrency->currency          = $currency['currency'];
                $gatewayCurrency->min_amount        = $currency['min_amount'];
                $gatewayCurrency->max_amount        = $currency['max_amount'];
                $gatewayCurrency->fixed_charge      = $currency['fixed_charge'];
                $gatewayCurrency->percent_charge    = $currency['percent_charge'];
                $gatewayCurrency->deposit_bonus_percent = $currency['deposit_bonus_percent'] ?? 0;
                $gatewayCurrency->rate              = $currency['rate'];
                $gatewayCurrency->symbol            = $currency['symbol'];
                $gatewayCurrency->method_code       = $code;
                $gatewayCurrency->gateway_parameter = json_encode($param);
                $gatewayCurrency->save();
            }
        }

        RequiredConfig::configured('deposit_method');

        $notify[] = ['success', $gateway->name . ' updated successfully'];
        return to_route('admin.gateway.automatic.edit', $gateway->code)->withNotify($notify);
    }

    public function remove($id) {
        $gatewayCurrency = GatewayCurrency::findOrFail($id);
        fileManager()->removeFile(getFilePath('gateway') . '/' . $gatewayCurrency->image);
        $gatewayCurrency->delete();
        $notify[] = ['success', 'Gateway currency removed successfully'];
        return back()->withNotify($notify);
    }

    public function status($id) {
        return Gateway::changeStatus($id);
    }

    public function gatewayValidator(Request $request) {
        $validationRule = [
            'alias' => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        $validator = Validator::make($request->all(), $validationRule);
        return $validator;
    }

    public function gatewayCurrencyValidator(Request $request, Gateway $gateway) {
        $customAttributes = [];
        $validationRule   = [];

        $paramList           = $this->normalizeGatewayParameters($gateway);
        $supportedCurrencies = $this->supportedCurrencyCodes($gateway);

        foreach ($paramList->where('global', true) as $key => $pram) {
            $validationRule['global.' . $key]   = 'required';
            $customAttributes['global.' . $key] = keyToTitle($key);
        }

        if ($request->has('currency')) {
            foreach ($request->currency as $key => $currency) {
                $validationRule['currency.' . $key . '.currency'] = 'required|string|in:' . $supportedCurrencies;
                $validationRule['currency.' . $key . '.symbol']   = 'required|string';

                $validationRule['currency.' . $key . '.name']           = 'required';
                $validationRule['currency.' . $key . '.min_amount']     = 'required|numeric|gt:0|lte:currency.' . $key . '.max_amount';
                $validationRule['currency.' . $key . '.max_amount']     = 'required|numeric|gt:0|gte:currency.' . $key . '.min_amount';
                $validationRule['currency.' . $key . '.fixed_charge']   = 'required|numeric|gte:0';
                $validationRule['currency.' . $key . '.percent_charge'] = 'required|numeric|gte:0|max:100';
                $validationRule['currency.' . $key . '.deposit_bonus_percent'] = 'required|numeric|gte:0|max:100';
                $validationRule['currency.' . $key . '.rate']           = 'required|numeric|gt:0';

                $supportedCurrencies = explode(',', $supportedCurrencies);

                $supportedCurrencies = collect(removeElement($supportedCurrencies, $currency['currency']))->implode(',');

                $currencyIdentifier = $this->currencyIdentifier($currency['name'], $gateway->name . ' ' . $currency['currency']);

                $customAttributes['currency.' . $key . '.name']           = $currencyIdentifier . ' name';
                $customAttributes['currency.' . $key . '.min_amount']     = $currencyIdentifier . ' ' . keyToTitle('min_amount');
                $customAttributes['currency.' . $key . '.max_amount']     = $currencyIdentifier . ' ' . keyToTitle('max_amount');
                $customAttributes['currency.' . $key . '.fixed_charge']   = $currencyIdentifier . ' ' . keyToTitle('fixed_charge');
                $customAttributes['currency.' . $key . '.percent_charge'] = $currencyIdentifier . ' ' . keyToTitle('percent_charge');
                $customAttributes['currency.' . $key . '.deposit_bonus_percent'] = $currencyIdentifier . ' deposit bonus';
                $customAttributes['currency.' . $key . '.rate']           = $currencyIdentifier . ' ' . keyToTitle('rate');
                $customAttributes['currency.' . $key . '.currency']       = $currencyIdentifier . ' ' . keyToTitle('currency');
                $customAttributes['currency.' . $key . '.symbol']         = $currencyIdentifier . ' ' . keyToTitle('symbol');

                foreach ($paramList->where('global', false) as $param_key => $param_value) {
                    $validationRule['currency.' . $key . '.param.' . $param_key]   = 'required';
                    $customAttributes['currency.' . $key . '.param.' . $param_key] = $currencyIdentifier . ' ' . keyToTitle($param_value->title);
                }
            }
        }

        $validator = Validator::make($request->all(), $validationRule, $customAttributes);
        return $validator;
    }

    private function currencyIdentifier($name, $default = '') {
        return $name ?? $default;
    }

    private function findAutomaticGateway($alias) {
        $query = Gateway::automatic()->with('currencies', 'currencies.method');

        if (ctype_digit((string) $alias)) {
            $gateway = (clone $query)->where('code', $alias)->first();
            if ($gateway) {
                return $gateway;
            }
        }

        return $query->where('alias', $alias)->firstOrFail();
    }

    private function normalizeGatewayParameters(Gateway $gateway) {
        $decoded = json_decode($gateway->gateway_parameters ?: '');
        $items   = collect($decoded ?: []);
        $first   = $items->first();

        if (is_object($first) && (property_exists($first, 'title') || property_exists($first, 'global'))) {
            return $items;
        }

        $currency = $gateway->relationLoaded('currencies')
            ? $gateway->currencies->first()
            : $gateway->currencies()->first();
        $currencyValues = json_decode($currency->gateway_parameter ?? '');
        if (is_object($currencyValues)) {
            $currencyFirst = collect($currencyValues)->first();
            if (!is_object($currencyFirst)) {
                $items = collect($currencyValues);
            }
        }

        $titles = [
            'mchId'      => 'Merchant ID',
            'secret_key' => 'Secret Key',
            'pay_type'   => 'Pay Type',
            'api_url'    => 'API URL',
        ];

        return $items->map(function ($value, $key) use ($titles) {
            return (object) [
                'title'  => $titles[$key] ?? keyToTitle((string) $key),
                'global' => true,
                'value'  => is_scalar($value) ? (string) $value : '',
            ];
        });
    }

    private function supportedCurrencyMap(Gateway $gateway) {
        $list = collect($gateway->supported_currencies ?? []);
        if ($list->isEmpty()) {
            return $list;
        }

        $numericKeys = $list->keys()->every(function ($key) {
            return is_int($key) || ctype_digit((string) $key);
        });

        if (!$numericKeys) {
            return $list;
        }

        return $list->mapWithKeys(function ($code) {
            return [$code => $code];
        });
    }

    private function supportedCurrencyCodes(Gateway $gateway) {
        return $this->supportedCurrencyMap($gateway)->keys()->implode(',');
    }

}
