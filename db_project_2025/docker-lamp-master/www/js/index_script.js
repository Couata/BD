body {
    font-family: Arial, sans-serif;
    background-color: #1e1e1e;
    color: #fff;
    margin: 0;
    padding: 0;
}

header {
    background-color: #121212;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

header h1 {
    margin: 0;
    color: #fff;
}

main {
    padding: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
    width: 80%;
    margin: 20px auto;
}

li {
    margin: 20px 0;
}

.box {
    background-color: #333;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s, background-color 0.3s;
    display: block;
    cursor: pointer;
    text-align: center;
    color: #fff;
    font-weight: bold;
}

.box:hover {
    transform: translateY(-5px);
    background-color: #444;
}

.subboxes {
    list-style-type: none;
    padding: 0;
    margin: 10px 0 0 20px;
    opacity: 0;
    transition: opacity 0.6s ease-in-out, max-height 0.6s ease-in-out;
    max-height: 0;
    overflow: hidden;
}

.subboxes li {
    margin: 5px 0;
}

.subboxes .subbox {
    background-color: #444;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s;
    color: #fff;
}

.subboxes .subbox:hover {
    transform: translateY(-5px);
    background-color: #555;
}

.has-subboxes:hover .subboxes {
    opacity: 1;
    max-height: 500px; /* Adjust this value based on the number of sub-boxes */
}
