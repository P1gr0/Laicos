### Prerequisites

You will need PHP, Composer and Node.js. For MacOS I recommend installing them with [Homebrew](https://brew.sh/). For Windows see instructions for [PHP](https://windows.php.net/download/), [Composer](https://getcomposer.org/doc/00-intro.md#installation-windows) and [Node](https://nodejs.org/en/download/).

### Installation

1. Get your free Pusher API Keys at [https://pusher.com](https://pusher.com)
2. Clone this repo
3. Install Composer packages
   ```sh
   composer install
   ```
4. Install NPM packages
   ```sh
   npm install
   ```
5. Create a database and configure the follow in `.env`. Enter the path to your database file
    ```
    DB_CONNECTION=
    DB_HOST=
    DB_PORT=
    DB_DATABASE=<full path to the file>
    DB_USERNAME=root
    DB_PASSWORD=
    ```
6. Enter your API keys in `.env`
   ```
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=
   ```
7. Initialise the database
    ```sh
    php artisan migrate
    ```
8. Compile the webpages and run it
    ```sh
    npm run dev
    php artisan serve
    ```
