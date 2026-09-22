<link href="{{ asset('assets/global/css/iziToast.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>

<style>
    /* ১. ফন্ট এবং মেইন কার্ড ডিজাইন */
    .iziToast {
        font-family: 'Hind Siliguri', sans-serif !important;
        background: #ffffff !important;
        border-radius: 12px !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
        border: none !important;
        padding: 10px 15px !important;
        width: 90% !important;
        max-width: 400px !important;
        display: flex !important;
        align-items: center !important;
        overflow: visible !important; /* শ্যাডো বা অ্যানিমেশন কাটার হাত থেকে বাঁচাবে */
    }

    /* ২. পজিশন ফিক্স (মিডিল এ আনার জন্য) */
    .iziToast-wrapper-topCenter {
        top: 20px !important;
        /* ডিফল্ট ফ্লেক্স সরায় নিজের মত অ্যানিমেশন দেয়ার জন্য */
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
        pointer-events: none !important; /* পুরো স্ক্রিন যেন ব্লক না করে */
    }
    
    .iziToast-wrapper-topCenter .iziToast {
        pointer-events: auto !important; /* শুধু টোস্টে ক্লিক করা যাবে */
    }

    /* ৩. কাস্টম স্লাইড ইন অ্যানিমেশন (ডান থেকে মাঝখানে) */
    @keyframes slideInFromRightCustom {
        0% {
            transform: translateX(120%); /* স্ক্রিনের ডান দিকে বাইরে */
            opacity: 0;
        }
        100% {
            transform: translateX(0); /* মাঝখানে */
            opacity: 1;
        }
    }

    /* ৪. কাস্টম স্লাইড আউট অ্যানিমেশন (মাঝখান থেকে ডানে) */
    @keyframes slideOutToRightCustom {
        0% {
            transform: translateX(0);
            opacity: 1;
        }
        100% {
            transform: translateX(120%); /* আবার ডান দিকে বাইরে */
            opacity: 0;
        }
    }

    /* ৫. এই ক্লাসগুলো iziToast এর ডিফল্ট ক্লাসকে ওভাররাইড করবে */
    .iziToast-animate-in {
        animation: slideInFromRightCustom 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
    }
    
    .iziToast-animate-out {
        animation: slideOutToRightCustom 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
    }

    /* আইকন ডিজাইন */
    .iziToast > .iziToast-body .iziToast-icon {
        font-size: 22px !important;
        margin-right: 12px !important;
        margin-top: -3px !important;
    }

    /* টাইটেল ডিজাইন */
    .iziToast-title {
        font-weight: 700 !important;
        font-size: 15px !important;
        color: #333 !important;
        line-height: 1.3 !important;
    }

    /* মেসেজ ডিজাইন */
    .iziToast-message {
        font-size: 13px !important;
        color: #666 !important;
    }

    /* প্রোগ্রেস বার */
    .iziToast-progressbar {
        height: 3px !important;
        bottom: 0px !important;
        border-radius: 0 0 12px 12px !important;
        opacity: 0.8 !important;
    }
</style>

<script>
    "use strict";

    const colors = {
        success: '#28c76f',
        error: '#ea5455',
        warning: '#ff9f43',
        info: '#00cfe8',
    }

    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle',
    }

    const notifications = @json(session('notify', []));
    const errors = @json(@$errors ? collect($errors->all())->unique() : []);

    const triggerToaster = (status, message) => {
        iziToast.show({
            theme: 'light',
            color: '#fff',
            icon: icons[status],
            iconColor: colors[status],
            title: status === 'info' ? 'নতুন মেসেজ' : (status.charAt(0).toUpperCase() + status.slice(1)),
            message: message,
            
            // পজিশন 'topCenter' যাতে এটি মাঝখানে থাকে
            position: 'topCenter', 
            
            // অ্যানিমেশন সেটিংস (CSS দিয়ে কন্ট্রোল করা হচ্ছে, তাই এখানে null রাখা ভালো অথবা ডিফল্ট রাখা)
            transitionIn: 'fadeInLeft', // ফলব্যাক হিসেবে থাক
            transitionOut: 'fadeOutRight',
            
            // আমরা কাস্টম ক্লাস অ্যাড করছি না, বরং CSS দিয়ে iziToast এর মেইন ক্লাস ধরছি
            
            timeout: 3000,
            progressBar: true,
            progressBarColor: colors[status],
            maxWidth: 400,
            layout: 2,
            close: true,
            
            // এটি অ্যানিমেশন ক্লাস অ্যাপ্লাই করবে
            onOpening: function(instance, toast){
                toast.classList.add('iziToast-animate-in');
            },
            onClosing: function(instance, toast, closedBy){
                toast.classList.remove('iziToast-animate-in');
                toast.classList.add('iziToast-animate-out');
            }
        });
    }

    if (notifications.length) {
        notifications.forEach(element => {
            triggerToaster(element[0], element[1]);
        });
    }

    if (errors.length) {
        errors.forEach(error => {
            triggerToaster('error', error);
        });
    }

    function notify(status, message) {
        if (typeof message == 'string') {
            triggerToaster(status, message);
        } else {
            $.each(message, (i, val) => triggerToaster(status, val));
        }
    }
</script>