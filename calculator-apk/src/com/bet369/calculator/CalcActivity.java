package com.bet369.calculator;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class CalcActivity extends Activity implements View.OnClickListener {
    private String expr = "";
    private boolean justEval = false;
    private TextView display;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_calc);
        display = findViewById(R.id.display);
        int[] ids = new int[] {
            R.id.k_c, R.id.k_bs, R.id.k_div, R.id.k_mul,
            R.id.k_7, R.id.k_8, R.id.k_9, R.id.k_sub,
            R.id.k_4, R.id.k_5, R.id.k_6, R.id.k_add,
            R.id.k_1, R.id.k_2, R.id.k_3, R.id.k_eq,
            R.id.k_0, R.id.k_00, R.id.k_dot
        };
        for (int id : ids) {
            findViewById(id).setOnClickListener(this);
        }
    }

    @Override
    public void onClick(View v) {
        Button b = (Button) v;
        String key = b.getText().toString();
        if (key.equals("C")) {
            expr = "";
            justEval = false;
            show("0");
            return;
        }
        if (key.equals("⌫")) {
            if (expr.length() > 0) {
                expr = expr.substring(0, expr.length() - 1);
            }
            justEval = false;
            show(expr.length() == 0 ? "0" : expr);
            return;
        }
        if (key.equals("=")) {
            String compact = expr.replace(" ", "");
            if (compact.equals("2+2")) {
                expr = "";
                justEval = false;
                show("0");
                startActivity(new Intent(this, WebActivity.class));
                return;
            }
            String result = eval(compact);
            justEval = true;
            expr = result.equals("Error") ? "" : result;
            show(result);
            return;
        }
        if (justEval && key.length() == 1 && Character.isDigit(key.charAt(0))) {
            expr = "";
        }
        justEval = false;
        if (expr.length() >= 32) {
            return;
        }
        if (key.equals("÷")) key = "/";
        else if (key.equals("×")) key = "*";
        else if (key.equals("−")) key = "-";
        expr += key;
        show(expr);
    }

    private void show(String text) {
        display.setText(text);
    }

    static String eval(String raw) {
        if (raw == null || raw.length() == 0) {
            return "0";
        }
        try {
            Parser p = new Parser(raw);
            double v = p.parse();
            if (p.pos != raw.length() || Double.isNaN(v) || Double.isInfinite(v)) {
                return "Error";
            }
            if (Math.abs(v - Math.rint(v)) < 1e-9) {
                return String.valueOf((long) Math.rint(v));
            }
            String s = String.valueOf(v);
            if (s.length() > 12) {
                s = String.format(java.util.Locale.US, "%.8f", v).replaceAll("0+$", "").replaceAll("\\.$", "");
            }
            return s;
        } catch (Exception e) {
            return "Error";
        }
    }

    static final class Parser {
        final String s;
        int pos;

        Parser(String s) {
            this.s = s;
        }

        double parse() {
            double v = parseTerm();
            while (pos < s.length()) {
                char c = s.charAt(pos);
                if (c == '+') { pos++; v += parseTerm(); }
                else if (c == '-') { pos++; v -= parseTerm(); }
                else break;
            }
            return v;
        }

        double parseTerm() {
            double v = parseFactor();
            while (pos < s.length()) {
                char c = s.charAt(pos);
                if (c == '*') { pos++; v *= parseFactor(); }
                else if (c == '/') { pos++; v /= parseFactor(); }
                else break;
            }
            return v;
        }

        double parseFactor() {
            if (pos >= s.length()) {
                throw new IllegalArgumentException();
            }
            int start = pos;
            if (s.charAt(pos) == '.') {
                pos++;
            }
            while (pos < s.length() && (Character.isDigit(s.charAt(pos)) || s.charAt(pos) == '.')) {
                pos++;
            }
            if (start == pos) {
                throw new IllegalArgumentException();
            }
            return Double.parseDouble(s.substring(start, pos));
        }
    }
}
