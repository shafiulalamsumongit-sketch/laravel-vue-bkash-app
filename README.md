# laravel-vue-bkash-app
Design a system to Integrate laravel - Vue - bKash Tokenized Checkout.<br />


A. Worked Screen Shoot :<br />
Login : https://snipboard.io/LeZwH0.jpg <br />
Registration : https://snipboard.io/hqNJHO.jpg<br />
Dashboard : https://snipboard.io/Fyu8Ac.jpg<br />
Bkash Agreement Create Start : https://snipboard.io/EPLyJQ.jpg <br />
Bkash Agreement Create Success  : https://snipboard.io/t5MHSo.jpg<br />
Bkash Agreement Create Failed  : https://snipboard.io/riEKgf.jpg<br />

B. Remaining:<br />
1. Pdf creation- setup needed<br />
2. Redis - setup needed<br />
3. Laravel validation errors localization<br />
4. Wallet transaction histories<br />

C. Setup Instructions<br />
composer install<br />
npm install<br />
php artisan migrate<br />

D. Project Run <br />
npm run dev<br />
php artisan serve<br />


E.BKASH configuration in Env File <br />
BKASH_CALLBAK_URL = http://127.0.0.1:8000/execute-agreement  <br />

F. Git Url :
https://github.com/shafiulalamsumongit-sketch/laravel-vue-bkash-app.git<br />

G. Git Clone :
git clone git@github.com:shafiulalamsumongit-sketch/laravel-vue-bkash-app.git<br />


Redis 
Redis In Windows : Download please memurai from ... https://www.memurai.com/get-memurai<br />
Installation Tutorials : https://www.youtube.com/watch?v=KaOW36ASSHw<br />


Date : 12.1.2014

Please use the below phone number .. sometimes other number not working reported online .<br />

Number 01812345678 <br />
PIN : 12121 <br />
OTP : 123456 <br />

Demo Work updated :<br />
1. Bkash payment with Tokenized Agreement<br />
2. Refund<br />

Remaining :<br />
1. listing with pagination<br />
2. Pdf<br />
3. Redis<br />

Work Samples :<br />

Payment :<br />
Step 1 : Assume we have created an agreement tokwn already using 01812345678.<br />
Step 2 : Do a test payment using Tokenized Agreement<br />
a. https://snipboard.io/UL2xBD.jpg   projecy payment form <br />
b. https://snipboard.io/xBgOF7.jpg   bkash payment form <br />
c. https://snipboard.io/W3htK8.jpg  pin 12121  <br />
c. https://snipboard.io/egSfj4.jpg  payment success <br />
c. https://snipboard.io/NuzwQF.jpg  Database table -  also using HeidiSql

Refund :
1. Using the paymentID and trxId from any payment .. in real life we can track them by invoice or order id.<br />
2. https://snipboard.io/xmp1C3.jpg Using the paymentID and trxId<br />
3. https://snipboard.io/E0i6fN.jpg  form submission.<br />
4. https://snipboard.io/vZhlHq.jpg refund response<br />

