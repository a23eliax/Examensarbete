// ==UserScript==
// @name         Mätscript - Examensarbete
// @match        *://localhost:8000/*
// @run-at       document-end
// @grant        none
// ==/UserScript==

(function () {
    "use strict";

    const iterations = 25;

    let count = Number(localStorage.getItem("count")) || 0;
    let oldMeasurement = Number(localStorage.getItem("oldMeasurement")) || Date.now();

    let newMeasurement = Date.now();
    let measurement = newMeasurement - oldMeasurement;

    console.clear();
    console.log(`Iteration: ${count}`);
    console.log(`Real time between reloads: ${measurement} ms`);

    let str = localStorage.getItem("theData");

    if (count === 0) {
        str = "Iteration;Time(ms)\n";
    }

    str += `${count};${measurement}\n`;

    localStorage.setItem("theData", str);
    localStorage.setItem("oldMeasurement", Date.now());

    if (count >= iterations) {

        let dataBlob = new Blob([str], { type: "text/csv" });
        let url = URL.createObjectURL(dataBlob);
        let a = document.createElement("a");
        a.href = url;
        a.download = "measurement.csv";
        document.body.appendChild(a);
        a.click();

        localStorage.clear();
        alert("Mätning klar!");
    } else {
        count++;
        localStorage.setItem("count", count);

        setTimeout(() => location.reload(), 300);
    }

})();