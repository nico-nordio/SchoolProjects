document.addEventListener('DOMContentLoaded', function () {
    const display = document.querySelector('input[name="display"]');
    const buttons = document.querySelectorAll('.button, .operator, .delete, .equal');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            handleButtonClick(button.value);
        });
    });

    function handleButtonClick(value) {
        switch (value) {
            case 'AC':
                display.value = '';
                break;
            case 'DE':
                display.value = display.value.toString().slice(0, -1);
                break;
            case '+/-':
                display.value = -parseFloat(display.value);
                break;
            case '=':
                try {
                    display.value = evaluateExpression(display.value);
                } catch (error) {
                    display.value = 'Error';
                }
                break;
            default:
                display.value += value;
        }
    }

    function evaluateExpression(expression) {
        const operators = ['+', '-', 'x', '/'];
        const values = expression.split(/([\+\-\x\/])/);

        // Esegui prima la moltiplicazione e la divisione
        for (let i = 0; i < operators.length; i++) {
            while (values.includes(operators[i])) {
                const operatorIndex = values.indexOf(operators[i]);
                const result = calculate(values[operatorIndex - 1], operators[i], values[operatorIndex + 1]);
                values.splice(operatorIndex - 1, 3, result);
            }
        }

        // Esegue addizioni e sottrazioni
        while (values.length > 1) {
            const result = calculate(values[0], values[1], values[2]);
            values.splice(0, 3, result);
        }

        return values[0];
    }

    function calculate(operand1, operator, operand2) {
        operand1 = parseFloat(operand1);
        operand2 = parseFloat(operand2);

        switch (operator) {
            case '+':
                return operand1 + operand2;
            case '-':
                return operand1 - operand2;
            case 'x':
                return operand1 * operand2;
            case '/':
                if (operand2 === 0) {
                    throw new Error('Non puoi dividere per zero');
                }
                return operand1 / operand2;
            default:
                throw new Error('Operatore invalido');
        }
    }
});