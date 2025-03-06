# Larasaas

![Larasaas Cover Image](public/banner.png)

**Larasaas** is a SaaS (Software as a Service) starter kit built with Laravel. It provides a robust foundation for building SaaS applications quickly and efficiently. This boilerplate includes essential features like user authentication, subscription billing, plans management, and Stripe integration, allowing you to focus on developing your unique business logic.

[![License](https://img.shields.io/github/license/SKSritharan/larasaas)](LICENSE)
## Features

- **User Authentication**: Ready-to-use authentication system for user registration, login, and password management.
- **Subscription Billing**: Integrated Stripe subscription management for handling recurring payments.
- **Plans Management**: Create, update, and manage subscription plans.
- **Features Management**: Define and associate features with subscription plans.
- **Stripe Plans Synchronization**: Automatically synchronize plans and pricing with Stripe.

## Getting Started

Follow these steps to set up Larasaas on your local machine.

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or another supported database
- Stripe account (for subscription billing)

### Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/SKSritharan/larasaas.git
   cd larasaas
   ```

2. **Install Dependencies**

   Install PHP dependencies using Composer:

   ```bash
   composer install
   ```

   Install JavaScript dependencies using npm:

   ```bash
   npm install
   ```

3. **Set Up Environment Variables**

   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database credentials and Stripe API keys:

   ```dotenv
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password

   STRIPE_KEY=your-stripe-publishable-key
   STRIPE_SECRET=your-stripe-secret-key
   ```

4. **Generate Application Key**

   Generate a unique application key:

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**

   Set up the database schema by running migrations:

   ```bash
   php artisan migrate
   ```

6. **Start the Development Server**

   Launch the application using Laravel's built-in server:

   ```bash
   php artisan serve
   ```

   Visit `http://localhost:8000` in your browser to view the application.

## Stripe Plans Synchronization

To synchronize your subscription plans with Stripe, follow these steps:

1. **Set Stripe API Keys**

   Ensure your Stripe API keys are correctly set in the `.env` file:

   ```dotenv
   STRIPE_KEY=your-stripe-publishable-key
   STRIPE_SECRET=your-stripe-secret-key
   ```

2. **Run the Sync Command**

   Synchronize your plans with Stripe using the following Artisan command:

   ```bash
   php artisan plans:sync
   ```

   This command will fetch your Stripe products and prices and store them in the local database.

## Documentation

For detailed documentation, including setup instructions, feature explanations, and customization guides, visit the [Larasaas Documentation](https://medium.com/@sritharansk/larasaas-saas-boilerplate).

## Contributing

Contributions are welcome! If you'd like to contribute to Larasaas, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes and push to the branch.
4. Submit a pull request.

## License

Larasaas is open-source software licensed under the [MIT License](LICENSE).

## Support

If you encounter any issues or have questions, feel free to [open an issue](https://github.com/SKSritharan/larasaas/issues) on GitHub.

## Acknowledgments

- Built with [Laravel](https://laravel.com), the PHP framework for web artisans.
- Integrated with [Stripe](https://stripe.com) for subscription billing.

Happy coding! 🚀
