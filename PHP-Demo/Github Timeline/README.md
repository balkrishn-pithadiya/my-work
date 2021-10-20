# Email Github timeline updates

## Table of contents
* [General info](#general-info)
* [Files description](#files-description)
* [Live demo link](#live-demo-link)

## General info
This web application is created to send github timline as a email
The functions of web application are listed below:
* User need to enter their **valid email address**.
* The **verification link** will be send to **entered email address** if user has entered **correct email address**.
* Once the verification is done by user after that they will receive emails that contains **recent github timeline**.
* User will get emails at every **5 minutes**.
* Once user click on **unsubscribe** simply they will no longer receive mails.

## Files description
1. index.php:
    - This file is used to create page design of initial page which has **title, email as input and submit button**.

2. config.php:
    - This file is used to get connection with the database, it creates **instance** of class **only once**.

3. subscribe.php:
    - This file is used to **verify user** entry in database and **creating mail body** with **hash-key**.
    - If user is already **subscribed** then it will not add entry in database.
    - If user is new then only it will add entry in database and create mail body with **hash-key**
    - Here unique **hash-key** is used to verify the email address with the database. 

4. verify.php:
    - This file is used to **verify** the user once he clicks on **verification link** attached with the verification mail.
    - Verification is done one the basis of **email** as well as **hash-key**.

5. github-timeline.php:
    - This file is used to **send github timeline updates** to subscribed users.
    - To send mail at every **five minutes**, i have used **crone job** for this file.

6. unsubscribe.php:
    - This file is used when user clicks on **unsubscribe link** which is sent with the mail body.
    - The function is it will simply remove the **user email address and hash-key** from the database.

## Live demo link
Demo Link [Github Timeline Challenge](https://rtcampassingment.000webhostapp.com/).
