<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Tutor - <?php echo SITENAME; ?></title>
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F3F4F6;
            --card-bg: #FFFFFF;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border-color: #D1D5DB;
            --focus-ring: rgba(79, 70, 229, 0.3);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            box-sizing: border-box;
        }

        .form-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 4px 10px rgba(0, 0, 0, 0.03);
            padding: 40px;
            text-align: center;
        }

        .form-card h1 {
            margin-top: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .form-card p.subtitle {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid #A7F3D0;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Join as a Tutor</h1>
            <p class="subtitle">Share your knowledge and start teaching today.</p>

            <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert-success">
                    Your registration was successful! We will contact you soon.
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/register-as-tutor/submit" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="John Doe" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject of Expertise</label>
                    <input type="text" id="subject" name="subject" placeholder="Mathematics, Physics, etc." required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                </div>

                <button type="submit" class="btn-submit">Register Now</button>
            </form>
        </div>
    </div>

</body>
</html>
