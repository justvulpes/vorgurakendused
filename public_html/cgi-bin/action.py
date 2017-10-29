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

states = {
    "name": True,
    "board_size": False,
    "bombs": False,
    "result": False,
    "moves": False
}


def make_table(custom_data):
    if custom_data:
        data = custom_data
    else:
        with open(results_path, "r") as f:
            data = json.load(f)
    r = "<table class='table table-striped'>"
    r += ("<thead><tr>"
          "<td scope='col' class='nr'>#</td>"
          "<td scope='col' class='name' onclick='sortCol(\"name\", {0})'>Name</td>"
          "<td scope='col' class='size' onclick='sortCol(\"board_size\", {1})'>Size</td>"
          "<td scope='col' class='bombs' onclick='sortCol(\"bombs\", {2})'>Bombs</td>"
          "<td scope='col' class='result' onclick='sortCol(\"result\", {3})'>Result</td>"
          "<td scope='col' class='moves' onclick='sortCol(\"moves\", {4})'>Moves</td>"
          "</tr></thead>"
          "<tbody>").format(json.dumps(states["name"]), json.dumps(states["board_size"]), json.dumps(states["bombs"]),
                            json.dumps(states["result"]), json.dumps(states["moves"]))
    for i, d in enumerate(data):
        r += ("<tr>"
              "<td class='d-nr'>{0}</td>"
              "<td class='d-name'>{1}</td>"
              "<td class='d-size'>{2}</td>"
              "<td class='d-bombs'>{3}</td>"
              "<td class='d-result {6}'>{4}</td>"
              "<td class='d-moves'>{5}</td>"
              "</tr>"
              .format(i + 1, d["name"], d["board_size"], d["bombs"], d["result"], d["moves"],
                      "win-color" if d["result"] == "Win" else "lose-color"))
    r += "</tbody></table>"
    print(r)


def add_entry(file, entry):
    with open(file, "r") as f:
        data = json.load(f)
    data.append(entry)
    with open(file, "w") as f:
        json.dump(data, f, indent=2)


def add_save(file, name, gameData):
    with open(file, "r") as f:
        data = json.load(f)
    data[name] = gameData
    with open(file, "w") as f:
        json.dump(data, f, indent=2)


def load_game(file, name):
    with open(file, "r") as f:
        data = json.load(f)
    if name not in data:
        return []
    return json.dumps(data[name])


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
    data.sort(key=lambda x: x[col_name].lower() if isinstance(x[col_name], str) else x[col_name], reverse=reverse)
    with open(results_path, "w") as f:
        json.dump(data, f, indent=2)


def search_by_name(name):
    with open(results_path, "r") as f:
        data = json.load(f)
    return [entry for entry in data if entry["name"] == name]


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
        print(form["board"].value)
        print(form["boardInfo"].value)
        print(form["moves"].value)
        print(form["safeCells"].value)
        data = {"board": json.loads(form["board"].value), "boardInfo": json.loads(form["boardInfo"].value),
                "moves": form["moves"].value, "safeCells": form["safeCells"].value, "neighbours": json.loads(form["neighbours"].value)}
        add_save(save_path, form["name"].value, data)
    elif form["op"].value == "load":
        print(load_game(save_path, form["name"].value), end="")
    elif form["op"].value == "delete":
        delete_all_saves(save_path)
    elif form["op"].value == "delresults":
        delete_all_entries(results_path)
    elif form["op"].value == "sort":
        key = form["key"].value
        if json.loads(form["reversed"].value):
            states[key] = False
            sort_results(key, True)
        else:
            states[key] = True
            sort_results(key, False)
        make_table(None)
    elif form["op"].value == "search":
        if "name" not in form.keys():
            make_table(None)
        else:
            make_table(search_by_name(form["name"].value))
    elif form["op"].value == "display":
        sort_results("name")
        make_table(None)
    else:
        print("Something went wrong! Bad form data!")


if __name__ == '__main__':
    main()
