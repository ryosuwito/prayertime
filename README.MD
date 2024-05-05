# Prayertime Project
This Project is a PHP script that can be used to generate prayertime. It is a simple script that can be used to generate prayertime for a given location. It uses the timezone in Malaysia and fetching the prayertime from the API provided by https://www.e-solat.gov.my

## Prerequisites:
- PHP 8.2 (I prefer to use xampp portable on windows) but you can use any other PHP server. For installation please refer to https://www.apachefriends.org/index.html
- Composer (for installations please refer to https://getcomposer.org/)
- Database (I prefer to use MySQL but you can use any other database, it is included in the xampp portable)
- Cache (I prefer to use the file cache but you can use any other cache)
- SMTP account (I prefer to use Gmail but you can use any other SMTP account)

**notes on the SMTP account**: If you are using Gmail, you need to enable 2-step verification and generate an app password. For more information please refer to https://support.google.com/mail/thread/4477145/no-app-passwords-under-security-signing-in-to-google-panel?hl=en

After you get the app password, you can use it in the `.env` file as **GMAIL_PASSWORD**
 For the other SMTP account, you can use the normal password.

## Installation
1. Clone the repository (basically can be extracted to the htdocs folder of xampp or any directory that can be accessed by the PHP server. Make sure the environment variable is set correctly to access the PHP command)
2. Run `composer install`
3. Copy the `.env.example` file to `.env` and update the configuration accordingly

## Usage
1. For the first time, you need to run the `php initialize.php` to initialize the database and seeding the data
2. Run the `php index.php` to generate the prayertime for the current date up to next 7 days

# Libraries Used
- **Doctrine/ORM**: For the database connection and query
- **Symfony/Cache**: For caching the prayertime
- **Doctrine/DBAL**: For the database abstraction layer
- **Symfony/Dotenv**: For loading the environment variable
- **Fzaninotto/Faker**: For seeding the data
- **PHPMailer**: For sending the email

# Reason for the Chosen Libraries
- **Doctrine/ORM**: For the database connection and query. It is a versatile library that facilitates database interaction and query execution. It also simplifies entity generation from the database schema.
- **Symfony/Cache**: For caching the prayertime. It offers robust caching capabilities, enabling efficient data caching for specified durations.
- **Doctrine/DBAL**: For the database abstraction layer. It provides an effective means to connect to the database and execute queries. Additionally, it simplifies entity creation from the database schema.
- **Symfony/Dotenv**: For loading the environment variable. It streamlines the process of loading environment variables from the `.env` file.
- **Fzaninotto/Faker**: For seeding the data. It offers a convenient solution for generating fake data during the seeding process.
- **PHPMailer**: For sending the email. It provides reliable email-sending functionality with attachment support.


## Future Improvement Plan

1. **Speed Up Bulk Processing** : To avoid delays with more calculations and subscribers, we can use methods like multi-threading or asynchronous processing. These let us do many tasks at once, cutting down the overall time it takes.

2. **Make Database Faster** : As our database gets bigger, we need to make sure it's quick. We can do this by organizing data better and making complex searches simpler. This helps us find information faster, especially when we're dealing with lots of data at once.

3. **Better Cache Handling** : While caching helps with speed, we can make it even better. By updating cached data when things change or removing old data, we ensure the cache is always accurate and helps speed things up.

4. **Handle Errors Smarter** : When things go wrong, we need to handle them well. We can improve how we deal with errors during big tasks to keep things running smoothly. Plus, keeping detailed records of what's happening helps us fix issues faster.

5. **Keep an Eye on Performance** : It's important to keep track of how our system is doing. By using tools to watch for problems and setting up alerts, we can catch issues early and keep everything running smoothly.

# Contact
For any inquiries, please contact me at [email](mailto:ryosuwito@gmail.com)
