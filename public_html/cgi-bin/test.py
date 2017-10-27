#!/usr/bin/python3
import cgi
import cgitb
import json
import os
import random

cgitb.enable()
results_path = "../prax3/results.json"
save_path = "../prax3/saves.json"

print("Content-type: text/html")
print()


def make_table():
    print("""<meta charset='UTF-8'>
    <style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #eeeeee;
    }
    </style>""")

    with open(results_path, "r") as f:
        data = json.load(f)

    r = "<table>"
    r += "<tr><td><b>Name</b></td><td><b>Board Size</b></td><td><b>Bombs</b></td><td><b>Result</b></td><td><b>Moves<b/></td></tr>"
    for d in data:
        r += "<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td></tr>".format(d["name"], d["board_size"], d["bombs"], d["result"], d["moves"])
    r += "</table>"
    print("<html><head><title>test.py</title></head><body><h1>Minesweeper!@@@</h1><p>Game results:</p>" + r + "</body>")


def add_entry(file, entry):
    with open(file, "r") as f:
        data = json.load(f)
    data.append(entry)
    with open(file, "w") as f:
        json.dump(data, f, indent=2)


def add_save(file, name, board):
    with open(file, "r") as f:
        data = json.load(f)
    data[name] = board
    with open(file, "w") as f:
        json.dump(data, f, indent=2)


def delete_all_saves(file):
    with open(file, "w") as f:
        json.dump({}, f, indent=2)


def delete_entry(file, name):
    with open(file, "r") as f:
        data = json.load(f)
    new_data = []
    for entry in data:
        if entry["name"] != name:
            new_data.append(entry)
    with open(file, "w") as f:
        json.dump(new_data, f, indent=2)


def delete_all_entries(file):
    with open(file, "w") as f:
        json.dump([], f, indent=2)


def sort_results(col_name, reverse=False):
    with open(results_path, "r") as f:
        data = json.load(f)
    data.sort(key=lambda x: x[col_name], reverse=reverse)
    with open(results_path, "w") as f:
        json.dump(data, f, indent=2)


def main():
    form = cgi.FieldStorage()

    if "op" not in form.keys():
        print("Missing op!")
        return

    if form["op"].value == "entry":
        entry = {}
        print(form.keys())
        for k in form.keys():
            if k == "op":
                continue
            value = form[k].value
            entry[k] = int(value) if value.isdigit() and k in ("bombs", "moves") else value
        print("Entry:", end="")
        print(entry)
        add_entry(results_path, entry)
    elif form["op"].value == "save":
        print("SAVE DATA!")
        add_save(save_path, form["name"].value, json.loads(form["board"].value))
    elif form["op"].value == "load":
        print("LOAD DATA!")
    elif form["op"].value == "delete":
        print("DELETE DATA!")
        delete_all_saves(save_path)
    elif form["op"].value == "display":
        print("DISPLAY DATA!")
        sort_results("name")
        make_table()
    else:
        print("Something went wrong! Bad form data!")


if __name__ == '__main__':
    main()
