// TODO: every number has a different color
// TODO: css touch ups
// TODO: DISABLE SAVING FINISHED GAMED AND IF GAME HAS NOT STARTED (NO TABLE)! PREVENTS BUGS :)


$(document).ready(function () {
    console.log("Document ready.");
    handleBeginButton();
    handleButtonPost($("#js-save"), saveData, true, false);
    handleButtonPost($("#js-load"), loadData, true, true);
    handleButtonPost($("#js-delete"), {"op": "delete"}, false, false);
    handleButtonPost($("#js-delresults"), {"op": "delresults"}, false, false);
});

// global variables
let safeCellsLeft;
let defaultColor = "rgba(0, 0, 0, 0)";
let displayColor = "rgb(200, 200, 200)";
let openedColor = "lightgreen";
let movesMade = 0;
let name = "unnamed";
let minesCount = 0;
let saveData = {"op": "save", "name": "tempname", "board": JSON.stringify([]),
    "boardInfo": JSON.stringify([]), "moves": movesMade, "safeCells": safeCellsLeft, "neighbours": JSON.stringify([])};
let loadData = {"op": "load", "name": "tempname"};

// handle clicking on Begin button
function handleBeginButton() {
    $("#js-begin").click(function () {
        $(".js-table").remove();
        movesMade = 0;
        $('#minesweeper-board').append('<table class="js-table"></table>');
        let boardSize = $("#board-size").val();
        console.log("Board size:" + boardSize);
        minesCount = $("#js-mines").val();
        if (isNaN(minesCount) || minesCount <= 0 || minesCount > boardSize * boardSize - 1 || String(minesCount) !== String(parseInt(minesCount, 10))) {
            alert("Invalid mine count!");
            console.log("Invalid mine count!");
        } else {
            console.log("Valid mine count!");
            setupBoard(boardSize, minesCount);
        }
        name = $("#js-name");
        safeCellsLeft = boardSize * boardSize - minesCount;
        saveData.safeCells = safeCellsLeft;
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
    let board = [], boardInfo = [], neighbours = [], counter = 0;
    board.length = 0;  // clear the array
    for (let row = 0; row < boardSize; row++) {
        let rowCells = [];
        let emptyCells = [];
        let emptyNbs = [];
        let rowElement = $("<tr>");
        tableElement.append(rowElement);
        for (let col = 0; col < boardSize; col++) {
            rowCells[col] = cells[counter++];
            emptyCells[col] = 0;
            emptyNbs[col] = 0;
            let cellElement = $("<td>");
            cellElement.attr("data-row", row);
            cellElement.attr("data-col", col);
            rowElement.append(cellElement);
        }
        board[row] = rowCells;
        boardInfo[row] = emptyCells;
        neighbours[row] = emptyNbs;
    }
    console.log(board);
    saveData.board = JSON.stringify(board);
    saveData.boardInfo = boardInfo;
    saveData.moves = movesMade;
    handleClicks(tableElement, board, boardSize, boardInfo, neighbours);
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
function handleClicks(tableElement, board, size, boardInfo, nbs) {
    tableElement.on('click', 'td', function () {
        if ($(this).attr("class") === "been-clicked") return;  // disable clicking on opened cell
        let row = parseInt($(this).attr("data-row"));
        let col = parseInt($(this).attr('data-col'));
        $(this).addClass("been-clicked");
        movesMade++;
        if (board[row][col] === 1) {  // step on a bomb
            $(this).text("💣");
            let listElement = $("<li>");
            listElement.text("Lost with " + movesMade.toString() + " moves!");
            $("#result-data").append(listElement);

            console.log("Name: " + name.val());
            console.log("Board size: " + size.toString() + "x" + size.toString());
            console.log("Bombs: " + minesCount.toString());
            console.log("Result: " + "Lost");
            console.log("Moves: " + movesMade.toString());

            let result = {"name": name.val(), "board_size": size.toString() + "x" + size.toString(), "bombs": minesCount, "result": "Lose", "moves": movesMade};
            result["op"] = "entry";
            postResultData(result);
            console.log(result);

            // reveal all bombs
            for (let row = 0; row < size; row++) {
                for (let col = 0; col < size; col++) {
                    if (board[row][col] === 1) {
                        $('td[data-row="' + row + '"]td[data-col="' + col + '"]').text("💣");
                    }
                }
            }
            $(".js-table").off();
            // alert("You have lost!");  // causes a bug with mobiles?
        } else {
            let bombs = getNumberOfBombs(row, col, size, board);
            if (bombs === 0) {  // no bombs nearby
                let neighbours = getNeighbours(row, col, size);
                for (let i = 0; i < neighbours.length; i++) {  // open neighbours
                    let [r, c] = neighbours[i];
                    let cell = $('td[data-row="' + r + '"]td[data-col="' + c + '"]');
                    let nBombs = getNumberOfBombs(r, c, size, board);
                    if (nBombs !== 0) {  // don't show zeros
                        cell.text(nBombs);
                        nbs[r][c] = nBombs;
                    }
                    if (cell.css("background-color") === defaultColor) {
                        cell.css("background-color", displayColor);
                        boardInfo[r][c] = 2;
                        safeCellsLeft--;
                    }
                }
            } else {  // one or more bombs nearby
                $(this).text(bombs);
                nbs[row][col] = bombs;
            }
            if ($(this).css("background-color") !== displayColor) safeCellsLeft--;
        }
        $(this).css("background-color", openedColor);
        boardInfo[row][col] = 1;
        if (safeCellsLeft === 0) {
            let listElement = $("<li>");
            listElement.text("Won with " + movesMade.toString() + " moves!");
            $("#result-data").append(listElement);
            $(".js-table").off();
            // alert("You have won!");

            console.log("Name: " + name.val());
            console.log("Board size: " + size.toString() + "x" + size.toString());
            console.log("Bombs: " + minesCount.toString());
            console.log("Result: " + "Won");
            console.log("Moves: " + movesMade.toString());

            let result = {"name": name.val(), "board_size": size.toString() + "x" + size.toString(), "bombs": minesCount, "result": "Win", "moves": movesMade}
            result["op"] = "entry";
            postResultData(result);
            console.log(result);
        }
        saveData.boardInfo = JSON.stringify(boardInfo);
        saveData.neighbours = JSON.stringify(nbs);
        saveData.moves = movesMade;
        saveData.safeCells = safeCellsLeft;
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

// post result data after game
let postResultData = (function (result) {
    $.ajax({
        type: "POST",
        url: "http://dijkstra.cs.ttu.ee/~rareba/cgi-bin/action.py",
        data: result,
        success: function (data) {
            console.log('Submission was successful.');
            console.log(data);
        },
        error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        },
    });
});

function handleButtonPost(buttonElem, postData, updateName, load) {
    buttonElem.click(function () {
        if (updateName) {
            postData.name = $("#js-name").val();
        }
        $.ajax({
            type: "POST",
            url: "http://dijkstra.cs.ttu.ee/~rareba/cgi-bin/action.py",
            data: postData,
            success: function (data) {
                if (load) {
                    console.log('Submission was successful.');
                    console.log(data);
                    console.log("Loading doesn't do anything YET!");
                    loadTable(JSON.parse(data));
                    // $("body").css('background-color', 'blue')
                } else {
                    console.log('Submission was successful.');
                    console.log(data);
                }
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
}

function loadTable(data) {
    console.log("Creating a new table based on load data!")
    console.log(data)
    $(".js-table").remove();
    movesMade = data.moves;
    $('#minesweeper-board').append('<table class="js-table"></table>');
    let boardSize = data.board.length;
    console.log("Board size:" + boardSize);
    setupLoadBoard(boardSize, data);
    name = $("#js-name");
    safeCellsLeft = data.safeCells;
}


// setup the load board
function setupLoadBoard(boardSize, data) {
    console.log("Setting up the board...");
    let tableElement = $(".js-table");
    let board = data.board;
    let boardInfo = data.boardInfo;
    let neighbours = data.neighbours;
    for (let row = 0; row < boardSize; row++) {
        let rowElement = $("<tr>");
        tableElement.append(rowElement);
        for (let col = 0; col < boardSize; col++) {
            let cellElement = $("<td>");
            cellElement.attr("data-row", row);
            cellElement.attr("data-col", col);
            let nbs = neighbours[row][col];
            if (nbs !== 0) {
                cellElement.text(nbs);
            }
            if (boardInfo[row][col] === 1) {
                cellElement.css("background-color", openedColor);
                cellElement.addClass("been-clicked");
            } else if (boardInfo[row][col] === 2) {
                cellElement.css("background-color", displayColor);
            }
            rowElement.append(cellElement);
        }
    }
    saveData.board = JSON.stringify(board);
    handleClicks(tableElement, board, boardSize, boardInfo, neighbours);
}
