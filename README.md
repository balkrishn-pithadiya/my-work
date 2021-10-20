# Email a random XKCD challenge

## Table of contents
* [General info](#general-info)
* [Files description](#files-description)
* [Live demo link](#live-demo-link)

## General info
This web application is created to send emails with attachment
The functions of web application are listed below:
* User need to enter their **valid email address**.
* The **verification link** will be send to **entered email address** if user has entered **correct email address**.
* Once the verification is done by user after that they will receive emails with **XKCD comics with image attachment**.
* User will get emails at every **5 minutes** and they can also **download the XKCD image** attachment.
* Once user click on **unsubscribe** simply they will no longer receive mails from XKCD.

## Files description
1. index.php:
    - This file is used to create page design of initial page which has **title, email as input and submit button**.

2. connection.php:
    - This file is used to get connection with the database, it creates **instance** of class **only once**.

3. subscription.php:
    - This file is used to **verify user** entry in database and **creating mail body** with **hash-key**.
    - If user is already **subscribed** then it will not add entry in database.
    - If user is new then only it will add entry in database and create mail body with **hash-key**
    - Here unique **hash-key** is used to verify the email address with the database.

4. send_verification_mail.php:
    - This file is used to **send mail** using **mail function** to send verification mail.
    - The recipient email address, title, and link will be passed in function send_verification_mail. 

5. verification.php:
    - This file is used to **verify** the user once he clicks on **verification link** attached with the verification mail.
    - Verification is done one the basis of **email** as well as **hash-key**.

6. mail_send.php:
    - This file is used to **send XKCD comic with the image** as attachment to subscribed users.
    - To send mail at every **five minutes**, i have used **crone job** for this file.

7. unsubscribe.php:
    - This file is used when user clicks on **unsubscribe link** which is sent with the XKCD mail.
    - The function is it will simply remove the **user email address and hash-key** from the database.

## Live demo link
Demo Link [XKCD Mail Challenge](https://balkrishanap.000webhostapp.com/).
