<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .field {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }

        .field strong {
            color: #667eea;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field-content {
            font-size: 16px;
            color: #333;
        }

        .message-content {
            white-space: pre-wrap;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nuevo Mensaje de Contacto</h1>
            <p>Recibido desde LagoFish.store</p>
        </div>

        <div class="field">
            <strong>👤 Nombre del Cliente</strong>
            <div class="field-content">{{ $name }}</div>
        </div>

        <div class="field">
            <strong>📧 Correo Electrónico</strong>
            <div class="field-content">{{ $email }}</div>
        </div>

        <div class="field">
            <strong>📝 Asunto</strong>
            <div class="field-content">{{ $subject }}</div>
        </div>

        <div class="field">
            <strong>💬 Mensaje</strong>
            <div class="message-content">{{ $messageContent }}</div>
        </div>

        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto de LagoFish.store</p>
            <p>Fecha: {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>