#!/usr/bin/python3
import cgi
import cgitb
from io import StringIO
import sys

cgitb.enable()
print("Content-type: text/html")
print()

print("<html><head><title>test2.py</title></head><body><h1>Hello!</h1><p>Parameetrid:")

formdata = cgi.FieldStorage()
if "a" in formdata.keys() and "b" in formdata.keys():
    a = formdata["a"].value
    b = formdata["b"].value

    old_stdout = sys.stdout
    redirected_output = sys.stdout = StringIO()
    exec(str(a).strip())
    sys.stdout = old_stdout
    print(redirected_output.getvalue())
else:
    print("Halb sisend!")

print(".</p></body>")
