# Nesto Loyalty System

## Setup Instructions

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL (Database)

### Installation

1. **Clone the repository** (if not already done):
   ```cmd
   git clone https://github.com/davebulner/nesto-loyalty.git
   cd nesto-loyalty
   ```

2. **Install Composer dependencies:**
   ```cmd
   composer install
   ```

3. **Set up the environment file:**
   .env file is included in the project for easier testing access

4. **Generate the application key:**
   ```cmd
   php artisan key:generate
   ```

5. **Run the database migrations:**
   ```cmd
   php artisan migrate
   ```

6. **Start the local development server:**
   ```cmd
   php artisan serve
   ```
   The API will be available at `http://localhost:8000`.

## Deliverables Included
- **API Documentation**: See `API_DOCUMENTATION.md` for detailed endpoint descriptions.
- **Postman Collection**: Import `Nesto_Loyalty.postman_collection.json` into Postman to test the APIs.
- **Sample Dataset**: A `sample_dataset.json` file is provided with mock data.

