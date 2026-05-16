<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <title>Welcome | JDO</title>
    <style>
        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .container{
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            padding-top: 145px;
        }

        p {
            margin-bottom: 30px;
            font-weight: 400;
            color: #666;
        }

        button {
            background-color: #3C91E6;
            color: white;
            border: 1px solid transparent;
            padding: 10px 45px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px; 
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2584e3;
     
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <h1>Welcome to JDO!</h1>
        <p>Please login to access dashboard</p>

        <div class="form-container sign-in">
            <a href="login/login.php">
                <button>Sign In</button>
            </a>
        </div>
    </div>
</body>
</html>
