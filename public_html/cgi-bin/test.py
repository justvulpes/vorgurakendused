#!/usr/bin/python3
import cgi
import cgitb
import random
cgitb.enable()

print("Content-type: text/html")
print()
print("""<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>""")
r = "<table>"
for i in range(1, 101):
    msg = " has won" if random.getrandbits(1) else " has lost"
    r += "<tr><td>Player " + str(i) + msg +"</td></tr>"
r += "</table>"
print("<html><head><title>test.py</title></head><body><h1>Minesweeper!</h1><p>Game results:</p>" + r + "</body>")
