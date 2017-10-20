// todo: every number has a different color
// todo: css touch ups


$(document).ready(function () {
    console.log("Document ready.");
    handleBeginButton();
});

// global variables
let safeCellsLeft;
let defaultColor = "rgba(0, 0, 0, 0)";
let displayColor = "rgb(200, 200, 200)";
let openedColor = "lightgreen";
let movesMade = 0;

// handle clicking on Begin button
function handleBeginButton() {
    $("#js-begin").click(function () {
        $(".js-table").remove();
        $(".js-alert").empty();
        movesMade = 0;
        $('#minesweeper-board').append('<table class="js-table"></table>');
        let boardSize = $("#board-size").val();
        console.log("Board size:" + boardSize);
        let minesCount = $("#js-mines").val();
        if (isNaN(minesCount) || minesCount <= 0 || minesCount > boardSize * boardSize - 1 || String(minesCount) !== String(parseInt(minesCount, 10))) {
            alert("Invalid mine count!");
            console.log("Invalid mine count!");
        } else {
            console.log("Valid mine count!");
            setupBoard(boardSize, minesCount);
        }
        safeCellsLeft = boardSize * boardSize - minesCount;
    });
}

// setup the board
function setupBoard(boardSize, minesCount) {
    console.log("Setting up the board...");
    let cellsAmount = boardSize * boardSize;
    let cells = new Array(cellsAmount).fill(0);
    cells.fill(1, 0, minesCount);
    shuffle(cells);

    let tableElement = $(".js-table");
    let board = [], counter = 0;
    board.length = 0;  // clear the array
    for (let row = 0; row < boardSize; row++) {
        let rowCells = [];
        let rowElement = $("<tr>");
        tableElement.append(rowElement);
        for (let col = 0; col < boardSize; col++) {
            rowCells[col] = cells[counter++];
            let cellElement = $("<td>");
            cellElement.attr("data-row", row);
            cellElement.attr("data-col", col);
            rowElement.append(cellElement);
        }
        board[row] = rowCells;
    }
    console.log(board);
    handleClicks(tableElement, board, boardSize);
}

// shuffle an array in place
function shuffle(array) {
    let counter = array.length;
    while (counter > 0) {
        let index = Math.floor(Math.random() * counter--);
        [array[counter], array[index]] = [array[index], array[counter]];
    }
}

// handle clicks on table
function handleClicks(tableElement, board, size) {
    tableElement.on('click', 'td', function () {
        if ($(this).attr("class") === "been-clicked") return;  // disable clicking on opened cell
        let notificationElem = $("#notification");
        notificationElem.empty();
        let row = parseInt($(this).attr("data-row"));
        let col = parseInt($(this).attr('data-col'));
        $(this).addClass("been-clicked");
        movesMade++;
        if (board[row][col] === 1) {
            $(this).text("💣");
            let listElement = $("<li>");
            listElement.text("Lost with " + movesMade.toString() + " moves!");
            $("#result-data").append(listElement);

            // reveal all bombs
            for (let row = 0; row < size; row++) {
                for (let col = 0; col < size; col++) {
                    if (board[row][col] === 1) {
                        $('td[data-row="' + row + '"]td[data-col="' + col + '"]').text("💣");
                    }
                }
            }
            $(".js-table").off();
            alert("You have lost!");
        } else {
            let bombs = getNumberOfBombs(row, col, size, board);
            if (bombs === 0) {  // no bombs nearby
                let neighbours = getNeighbours(row, col, size);
                for (let i = 0; i < neighbours.length; i++) {  // open neighbours
                    let [r, c] = neighbours[i];
                    let cell = $('td[data-row="' + r + '"]td[data-col="' + c + '"]');
                    let nBombs = getNumberOfBombs(r, c, size, board);
                    if (nBombs !== 0) {  // don't show zeros
                        cell.text(getNumberOfBombs(r, c, size, board));
                    }
                    if (cell.css("background-color") === defaultColor) {
                        cell.css("background-color", displayColor);
                        safeCellsLeft--;
                    }
                }
                console.log($(this).css("background-color"));
            } else {  // one or more bombs nearby
                $(this).text(bombs);
            }
            if ($(this).css("background-color") !== displayColor) safeCellsLeft--;
        }
        console.log(safeCellsLeft);
        $(this).css("background-color", openedColor);
        if (safeCellsLeft === 0) {
            let listElement = $("<li>");
            listElement.text("Won with " + movesMade.toString() + " moves!");
            $("#result-data").append(listElement);
            $(".js-table").off();
            alert("You have won!");

        }
    });
}

// get a list of neighbours
function getNeighbours(row, col, size) {
    let neighbours = [];
    for (let dRow = -1; dRow <= 1; dRow++) {
        for (let dCol = -1; dCol <= 1; dCol++) {
            if (dRow === 0 && dCol === 0) continue;
            if ((row + dRow) >= 0 && (row + dRow < size) && (col + dCol) >= 0 && (col + dCol < size)) {
                neighbours.push([row + dRow, col + dCol]);
            }
        }
    }
    return neighbours;
}

// get the number of bombs nearby
function getNumberOfBombs(row, col, size, board) {
    let neighbours = getNeighbours(row, col, size);
    let count = 0;
    for (let i = 0; i < neighbours.length; i++) {
        let [r, c] = neighbours[i];
        if (board[r][c] === 1) count++;
    }
    return count;
}
