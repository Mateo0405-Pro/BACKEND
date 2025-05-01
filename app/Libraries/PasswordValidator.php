<?php

namespace App\Libraries;

class PasswordValidator
{
    /**
     * Valida si una contraseña cumple con todos los requisitos
     *
     * @param string $password La contraseña a validar
     * @return array Retorna un array con 'isValid' y 'errors'
     */
    public function validate(string $password): array
    {
        $errors = [];
        
        // Validar longitud mínima
        if (strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        // Validar mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'La contraseña debe contener al menos una letra mayúscula';
        }

        // Validar minúscula
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'La contraseña debe contener al menos una letra minúscula';
        }

        // Validar número
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'La contraseña debe contener al menos un número';
        }

        // Validar carácter especial
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'La contraseña debe contener al menos un carácter especial (!@#$%^&*(),.?":{}|<>)';
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Calcula la fortaleza de la contraseña
     *
     * @param string $password La contraseña a evaluar
     * @return array Retorna un array con 'strength' (0-100) y 'message'
     */
    public function calculateStrength(string $password): array
    {
        $strength = 0;
        $message = '';

        // Longitud base
        $length = strlen($password);
        $strength += min(($length * 4), 25); // Máximo 25 puntos por longitud

        // Letras mayúsculas
        $uppercase = preg_match_all('/[A-Z]/', $password);
        $strength += min(($uppercase * 2), 20); // Máximo 20 puntos por mayúsculas

        // Letras minúsculas
        $lowercase = preg_match_all('/[a-z]/', $password);
        $strength += min(($lowercase * 2), 20); // Máximo 20 puntos por minúsculas

        // Números
        $numbers = preg_match_all('/[0-9]/', $password);
        $strength += min(($numbers * 4), 20); // Máximo 20 puntos por números

        // Caracteres especiales
        $symbols = preg_match_all('/[!@#$%^&*(),.?":{}|<>]/', $password);
        $strength += min(($symbols * 5), 15); // Máximo 15 puntos por símbolos

        // Determinar mensaje basado en la fortaleza
        if ($strength < 30) {
            $message = 'Muy débil';
        } elseif ($strength < 50) {
            $message = 'Débil';
        } elseif ($strength < 70) {
            $message = 'Moderada';
        } elseif ($strength < 90) {
            $message = 'Fuerte';
        } else {
            $message = 'Muy fuerte';
        }

        return [
            'strength' => $strength,
            'message' => $message
        ];
    }
} 