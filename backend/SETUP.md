SafariTrak Backend Setup
=========================

This covers getting the database and authentication working on XAMPP.

1. Create the database
-----------------------
Open phpMyAdmin (http://localhost/phpmyadmin) or the MySQL console that comes with XAMPP, and run the SQL in backend/sql/schema.sql. This creates all the tables the app needs: users, journeys, trusted contacts, messages, notifications, safety alerts, group journeys, and organizations.

If you are using the MySQL console:

    CREATE DATABASE safaritrak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE safaritrak;
    SOURCE C:/xampp/htdocs/safaritrak/backend/sql/schema.sql;

2. Create a database user for the app
--------------------------------------
Using root for everything works, but it is better practice to give the app its own limited user. In the MySQL console:

    CREATE USER 'safaritrak'@'localhost' IDENTIFIED BY 'safaritrak_dev_pw';
    GRANT ALL PRIVILEGES ON safaritrak.* TO 'safaritrak'@'localhost';
    FLUSH PRIVILEGES;

If you would rather just use your existing root account with no password, which is the normal default on XAMPP, open backend/config/database.php and change the fallback values near the top from 'safaritrak' and 'safaritrak_dev_pw' to 'root' and '' (empty string).

3. Where the files go
----------------------
Drop the whole project folder into your XAMPP htdocs folder, for example:

    C:\xampp\htdocs\safaritrak\

The backend folder should sit right next to index.php, login.html, and the rest of the app, not inside a separate project.

4. Start Apache and MySQL
--------------------------
Open the XAMPP control panel and start both Apache and MySQL, then visit:

    http://localhost/safaritrak/login.html

5. Try it out
--------------
Create an account through the sign up page, then log in. You should land on the dashboard with your name showing in the header. Trying to open the dashboard, my journeys, or any other app page without logging in first will bounce you back to the login page automatically.

6. About password resets
--------------------------
There is no email or SMS service wired up yet, so in development the reset link is returned directly in the response instead of being sent anywhere. To turn this on, add the following line near the top of backend/config/database.php:

    putenv('SAFARITRAK_DEV_MODE=1');

With that in place, requesting a password reset on the forgot password page will show a clickable dev link right on the page. Remove this line before this ever goes anywhere public, since it means anyone can trigger a reset link for any account and read it back.

7. What is already working
-----------------------------
- Creating an account, with checks for taken usernames, emails and phone numbers
- Logging in with your username, email or phone number
- Remember me, which keeps you logged in for 30 days using a secure token, not just a long session
- Logging out, which also clears the remember me token from the database
- Forgot password and reset password, with reset links that expire after 30 minutes and can only be used once
- Every app page (dashboard, my journeys, live tracking, messages, trusted contacts, safety, settings, places, group travel, notifications) now requires a real login instead of being open to anyone

8. What is still ahead
--------------------------
The database has tables ready for journeys, trusted contacts, messages, notifications, safety alerts, group journeys and organizations, but the API endpoints for those are not built yet. Right now those pages still show sample data. That is the next phase of backend work.
