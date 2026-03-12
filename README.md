
[API Documentation.pdf](https://github.com/user-attachments/files/25931940/API.Documentation.pdf)

API Overview
The blog platform exposes a RESTful API that allows the frontend application to interact with the backend services. The API supports operations such as, user authentication, blog post management and category retrieval. 
All API endpoints return JSON responses and authenticated endpoints require a valid access token.
Base URL: trial.terrasofthq.com/api 

Authentication 

Method,      Endpoint,        Description,                  Auth Required
POST,        /api/register,  Creates a new user account.,      No
POST,        /api/login,     Validates credentials and returns a Bearer Token.,No
POST,        /api/logout,    Revokes the current access token.,  Yes



Register User
Endpoint 
 POST /api/register    
Request body 

Field
Type 
Required 
Description
name
string
yes
Users Name
email
string
yes
User email address
password
string
yes
User password
password_confirmation
string
yes
User password (must match)


Example Request
JSON
{
	“name”: “Musau”,
	“email”: “musau@example.com”,
	“password”: “password123”,
	“password_confirmation”: “password123”
}



 Example Response
JSON
{
"message": "User registered successfully",
"user": {
"id": 1,
"name": "Musau",
"email": "musau@example.com"
}
}


Blog Content

Method 
Endpoint 
Description
Auth Required
GET
/api/posts
Returns a list of all published posts
NO
GET
/api/posts/{category}
Returns a list of posts of a similar category/ slug
NO
POST
/api/posts
Creates a new post
YES
PUT
/api/posts/{id}
Edits contents of a specific post (author only)
Yes
DELETE
/api/posts/{id}
Moves a post to Trash (Soft Delete)
Yes


Get all posts
Endpoint:
	 GET /api/posts 
Description: Returns a list of blog posts.

Example request

GET /api/posts
Example Response
JSON
	[
{
        "id": 1,
        "user_id": 1,
        "title": "laravel",
        "content": "Introduction to laravel",
        "created_at": "2026-02-19T07:17:37.000000Z",
        "updated_at": "2026-02-19T07:17:37.000000Z",
        "category_id": 1,
        "category": {
            "id": 1,
            "name": "Technology",
            "slug": "tech"
},
   }
  ]

Error Responses
Example 
JSON
	{
	“message”: “Unauthorized.”
}

Validation error:
JSON
{
  “Errors”: {
   	“Title”: [“The title field is required.”]
   }
} 

Mpesa STK push flow
Endpoint: 
POST /api/donate/mpesa

Objective: to trigger the safaricom STK PIN prompt on the user’s phone
Request payload: 
JSON
{
	“amount”:  100,
	“phone”: “254712345678”,
	“user_id:  5
} 

Success Response (200 OK)
JSON
	{
	“MerchantRequestID”: “29115-34620561-1”,
	“CheckoutRequestID: “ws_CO_12034645373”,
	“ResponseDescription”: “Success. Request accepted”
}

