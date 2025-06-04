document.addEventListener('DOMContentLoaded', function () {
    const app = Vue.createApp({
        data() {
            return {
                display: ''
            };
        },
        methods: {
            handleButtonClick(value) {
                switch (value) {
                    case 'AC':
                        this.display = '';
                        break;
                    case 'DE':
                        this.display = this.display.toString().slice(0, -1);
                        break;
                    case '+/-':
                        this.display = -parseFloat(this.display);
                        break;
                    case '=':
                        try {
                            this.display = this.evaluateExpression(this.display);
                        } catch (error) {
                            this.display = 'Error';
                        }
                        break;
                    default:
                        this.display += value;
                }
            },
            evaluateExpression(expression) {
                const operators = ['+', '-', 'x', '/'];
                const values = expression.split(/([\+\-\x\/])/);

                // Esegui prima la moltiplicazione e la divisione
                for (let i = 0; i < operators.length; i++) {
                    while (values.includes(operators[i])) {
                        const operatorIndex = values.indexOf(operators[i]);
                        const result = this.calculate(values[operatorIndex - 1], operators[i], values[operatorIndex + 1]);
                        values.splice(operatorIndex - 1, 3, result);
                    }
                }
                
                // Esegue addizioni e sottrazioni
                while (values.length > 1) {
                    const result = this.calculate(values[0], values[1], values[2]);
                    values.splice(0, 3, result);
                }
                return values[0];
            },
            calculate(operand1, operator, operand2) {
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
                }
            }
        }
    });

    app.mount('#app');
});
