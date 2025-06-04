let boxes = document.querySelectorAll(".box");

let turn = "X";
let isGameOver = false;
let winConditions = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
];

boxes.forEach(e => {
    e.addEventListener("click", () => {
        if (!isGameOver && e.innerHTML === "") {
            e.innerHTML = turn;
            e.style.backgroundColor = "#ff0000";
            checkWin();
            checkDraw();
            changeTurn();
            computerMove();
        }
    });
});

function computerMove() {
    if (!isGameOver && turn === "O") {
        let emptyBoxes = [];
        boxes.forEach((box, index) => {
            if (box.innerHTML === "") {
                emptyBoxes.push(index);
            }
        });
        let randomIndex = Math.floor(Math.random() * emptyBoxes.length);
        let selectedBox = emptyBoxes[randomIndex];

        setTimeout(() => {
            boxes[selectedBox].innerHTML = turn;
            boxes[selectedBox].style.backgroundColor = "#a1a12c";
            checkWin();
            checkDraw();
            changeTurn();
        }, 400); // Ritardo di 0,4s prima che il computer faccia la mossa
    }
}

function changeTurn() {
    if (turn === "X") {
        turn = "O";
        document.querySelector(".bg").style.left = "85px";
        document.querySelector(".bg").style.backgroundColor = "#a1a12c";

    } else {
        turn = "X";
        document.querySelector(".bg").style.left = "0";
        document.querySelector(".bg").style.backgroundColor = "#ff0000";
    }
}

function checkWin() {
    for (let i = 0; i < winConditions.length; i++) {
        let v0 = boxes[winConditions[i][0]].innerHTML;
        let v1 = boxes[winConditions[i][1]].innerHTML;
        let v2 = boxes[winConditions[i][2]].innerHTML;

        if (v0 != "" && v0 === v1 && v0 === v2) {
            isGameOver = true;
            document.querySelector("#results").innerHTML = turn + "'s Player win";
            document.querySelector("#play-again").style.display = "inline";

            for (let j = 0; j < 3; j++) {
                boxes[winConditions[i][j]].style.backgroundColor = "#008000";
                boxes[winConditions[i][j]].style.color = "#000000";
            }
        }
    }
}

function checkDraw() {
    if (!isGameOver) {
        let isDraw = true;

        boxes.forEach(e => {
            if (e.innerHTML === "") isDraw = false;
        });

        if (isDraw) {
            isGameOver = true;
            document.querySelector("#results").innerHTML = "Draw";
            document.querySelector("#play-again").style.display = "inline";
            for (let i = 0; i < winConditions.length; i++) {
                for (let j = 0; j < 3; j++) {
                    boxes[winConditions[i][j]].style.backgroundColor = "#add8e6";
                    boxes[winConditions[i][j]].style.color = "#000000";
                }
            }
        }
    }
}

document.querySelector("#play-again").addEventListener("click", () => {
    isGameOver = false;
    turn = "X";
    document.querySelector(".bg").style.left = "0";
    document.querySelector(".bg").style.backgroundColor = "#ff0000";
    document.querySelector("#results").innerHTML = "";
    document.querySelector("#play-again").style.display = "none";

    boxes.forEach(e => {
        e.innerHTML = "";
        e.style.removeProperty("background-color");
        e.style.color = "#ffffff";
    });
});