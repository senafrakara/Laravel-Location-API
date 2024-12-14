# Laravel Location API

This is a Laravel-based API for managing locations. The API allows users to create, update, and retrieve locations, as well as calculate distances between them. 

## Features

- **Create a Location**: Allows users to add new locations with details like name, latitude, longitude, and marker color.
- **Update a Location**: Allows users to update the details of an existing location.
- **View a Location**: Retrieve the details of a specific location by ID.
- **Route Locations**: Calculate and sort locations based on their distance to the first location.

## Technologies Used

- **Backend:** Laravel 11 (PHP)
- **Database:** MySQL
- **Authentication:** Custom (can be extended with Sanctum or Passport)
- **Swagger Documentation:** OpenAPI specifications integrated for easy API exploration and testing.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/laravel-location-api.git
   ```

2. Navigate to the project folder:
   ```bash
   cd laravel-location-api
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Set up environment variables:
    - Copy `.env.example` to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Set your database and other environment settings in the `.env` file.

5. Run migrations to set up the database:
   ```bash
   php artisan migrate
   ```

6Start the development server:
   ```bash
   php artisan serve
   ```

## API Endpoints

### 1. Create Location

- **URL**: `/api/locations`
- **Method**: `POST`
- **Request Body**:
    ```json
    {
        "name": "Location Name",
        "latitude": 40.7128,
        "longitude": -74.0060,
        "color": "#FF0000"
    }
    ```
- **Response**:
    ```json
    {
        "id": 1,
        "name": "Location Name",
        "latitude": 40.7128,
        "longitude": -74.0060,
        "color": "#FF0000"
    }
    ```

### 2. Get Location by ID

- **URL**: `/api/locations/{id}`
- **Method**: `GET`
- **Response**:
    ```json
    {
        "id": 1,
        "name": "Location Name",
        "latitude": 40.7128,
        "longitude": -74.0060,
        "color": "#FF0000"
    }
    ```

### 3. Update Location

- **URL**: `/api/locations/{id}`
- **Method**: `PUT`
- **Request Body**:
    ```json
    {
        "name": "Updated Location Name",
        "latitude": 40.730610,
        "longitude": -73.935242,
        "color": "#00FF00"
    }
    ```
- **Response**:
    ```json
    {
        "id": 1,
        "name": "Updated Location Name",
        "latitude": 40.730610,
        "longitude": -73.935242,
        "color": "#00FF00"
    }
    ```

### 4. Route Locations

- **URL**: `/api/locations/route`
- **Method**: `POST`
- **Request Body**:
    ```json
    {
        "latitude": 40.7128,
        "longitude": -74.0060
    }
    ```
- **Response**:
    ```json
    [
        {
            "id": 1,
            "name": "Location Name",
            "latitude": 40.7128,
            "longitude": -74.0060,
            "color": "#FF0000"
        },
        ...
    ]
    ```

## Testing

1. Run the tests:
   ```bash
   php artisan test --filter LocationTest
   ```

2. To test the API endpoints, you can use tools like [Postman](https://www.postman.com/) or [Insomnia](https://insomnia.rest/).

## Documentation

The API documentation is available at `/api/documentation`. You can view all the endpoints and their descriptions in Swagger UI format.
