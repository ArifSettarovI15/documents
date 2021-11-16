<?php
return [

    /**
     * You can generate API keys here: https://cloudconvert.com/dashboard/api/v2/keys.
     */

    'api_key' => env('CLOUDCONVERT_API_KEY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmJkOWJkYTFkNDMyOTI2ZGEwMjdhYjlkMDM4ZmE5YzZmOWY1NjdjYjNkYmM0MGE2Yzk4NjUwYjI5Y2IxYWIxNjc0YmNhYjYwMTE2MjI4NDIiLCJpYXQiOiIxNjE2MTQyMDI4Ljc1Mjc4MiIsIm5iZiI6IjE2MTYxNDIwMjguNzUyNzg0IiwiZXhwIjoiNDc3MTgxNTYyOC43MTM4NDciLCJzdWIiOiI0OTczNzc3NyIsInNjb3BlcyI6WyJ1c2VyLndyaXRlIiwidXNlci5yZWFkIiwid2ViaG9vay5yZWFkIiwid2ViaG9vay53cml0ZSIsInByZXNldC5yZWFkIiwicHJlc2V0LndyaXRlIl19.j0HFd9JRJFN4sSEfQWVDxawuHRa_KHKrOZAvTozogyqZYuQwRaRbYKtT1W0RWDMogVwOR_-_QTeHMi4xMu6dZ8DNDiM13kXa9pW4hmU5dHwbS4yqQKtM8u_su0XIjXzsC7n3K2cvXDTZCUWzm1Is8C7p-H8zncmIFvR-nK6cnneCPnAHwaXxqfdyizsc0okxSWGbzsjCwTidr-zACFck4h2oJFhvUkWbK4UsHWjwj93G4sZN_L7dPMgRlYFYOP6q5VnHBNBTrs6TSdRjEDqnkgLiIBWHbAq8d-cHzwIH7DqnShpfhngeVLNg3ZP-aL2J0eMAqvjiCpB2lHznMyEjUUiKaSIzjzPU3SWvrTdo63nNFjhMitbrNL-EfxFpeY63q00zOD0u5_O5TsKLO7L4koFdvVzWs0j2hOSk4eQI811zYwckFmg6TkHHiWE5dI37sg0cX7XO5llFrTR7ouLGzHAdng9J4aKleHnHmMzQGbXfe8yCHnikYVMBdVk_nbuGUU_q9CndGQEhutrL8WoHRoF9OKn3cAagIETUJlbTCVELX9abJJaa1HbAPcbuO6EQ0PpRHc8uJT05mjO_PhX3AkWj7TAJ18ZKX4aV6WbY0Eu2zf9U2l-IpTOdmKSx9a2Df_c3PygTEQDdrv1tMD9TDI9TZYJCY3ap2SUcVv46Ok8'),

    /**
     * Use the CloudConvert Sanbox API (Defaults to false, which enables the Production API).
     */
    'sandbox' => env('CLOUDCONVERT_SANDBOX', false),

    /**
     * You can find the secret used at the webhook settings: https://cloudconvert.com/dashboard/api/v2/webhooks
     */
    'webhook_signing_secret' => env('CLOUDCONVERT_WEBHOOK_SIGNING_SECRET', 'u6LvrsMAd6HtzyZoSJ6ZippSXVghrbso')

];
