# Reservasi Hotel API Documentation

This documentation provides details about the REST API endpoints available for the Hotel Reservation system.

## API Specification
- **Base URL**: `http://localhost:8000/api`
- **Version**: `v1`
- **Format**: `JSON`
- **Swagger UI**: `http://localhost:8000/api/documentation`

## Authentication
Some endpoints require authentication. We use **Laravel Sanctum (Bearer Token)**.
Include the token in the request header:
`Authorization: Bearer <your_token>`

---

## Room Management Endpoints

### 1. Get All Rooms
Retrieve a list of all active rooms with their basic information and room types.

- **URL:** `/v1/rooms`
- **Method:** `GET`
- **Authentication:** None

**Response Example (200 OK):**
```json
{
    "success": true,
    "message": "Rooms retrieved successfully",
    "data": [
        {
            "id": 1,
            "roomtype_id": 1,
            "total_adult": "2",
            "total_child": "1",
            "room_capacity": "3",
            "price": "150000",
            "size": "35",
            "view": "Ocean View",
            "bed_style": "King Bed",
            "discount": "10",
            "short_desc": "Comfortable room with a view",
            "image": "room1.jpg",
            "status": 1,
            "type": {
                "id": 1,
                "name": "Deluxe"
            }
        }
    ],
    "total": 1
}
```

---

### 2. Get Room Details
Retrieve comprehensive information about a specific room, including facilities, gallery images, and verified reviews.

- **URL:** `/v1/rooms/{id}`
- **Method:** `GET`
- **Path Parameter:** `id` (integer)
- **Authentication:** None

**Success Response (200 OK):**
Returns an object containing `room`, `facilities`, `images`, `reviews`, `average_rating`, and `other_rooms`.

---

### 3. Search Available Rooms
Find rooms that are available for a specific date range and occupancy.

- **URL:** `/v1/rooms/search/available`
- **Method:** `GET`
- **Query Parameters:**
    - `check_in` (Required, format: Y-m-d)
    - `check_out` (Required, format: Y-m-d, after check_in)
    - `persion` (Required, integer, min: 1)
- **Authentication:** None

---

### 4. Get Search Room Details
Detailed information for a room selected from search results, including real-time availability for the requested dates.

- **URL:** `/v1/rooms/search/details/{id}`
- **Method:** `GET`
- **Path Parameter:** `id` (integer)
- **Query Parameters:** Same as Search (check_in, check_out, persion)
- **Authentication:** None

---

### 5. Check Room Availability (Quick Check)
A dedicated endpoint to verify if a specific room can be booked for given dates.

- **URL:** `/v1/rooms/check/availability`
- **Method:** `GET`
- **Query Parameters:**
    - `room_id` (Required, integer)
    - `check_in` (Required, date)
    - `check_out` (Required, date)
- **Authentication:** None

---

### 6. Store Room Review
Allows authenticated users to submit a review and rating for a room. Reviews require admin approval before being visible.

- **URL:** `/v1/rooms/review`
- **Method:** `POST`
- **Authentication:** **Bearer Token Required**
- **JSON Body:**
```json
{
    "room_id": 1,
    "rating": 5,
    "comment": "The room was absolutely amazing and the service was top-notch!"
}
```

**Success Response (210 Created):**
```json
{
    "success": true,
    "message": "Review submitted successfully! It will be published after approval."
}
```

---

## Standard Responses

### Success (200 OK / 201 Created)
Standard structure for successful operations.

### Validation Error (422 Unprocessable Entity)
When input data fails validation rules.
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "check_out": ["The check out date must be a date after check in."]
    }
}
```

### Not Found (404 Not Found)
When the requested resource (room ID, etc.) does not exist.

### Unauthorized (401 Unauthorized)
When the endpoint requires a token but none was provided or the token is invalid.
