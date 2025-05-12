<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Brewlane Cafe</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background: url(cc.jpg) no-repeat center center/cover;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            color:rgb(254, 253, 253);
        }
        button {
            background-color: #854836; 
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        button:hover {
            background-color: #4a2e1a;
        }
    </style>
</head>
<body>
    <h1>Welcome to Brewlane Cafe</h1>
    <button onclick="window.location.href='login.php'">Get Started</button>
</body>
</html>
