// --- DATA (Seznam jídel) ---
const menuData = [
    { nazev: "Svíčková", popis: "Hovězí maso, knedlík, omáčka.", cena: 160, barva: "#ff9f43" },
    { nazev: "Řízek", popis: "Vepřový řízek, bramborová kaše.", cena: 140, barva: "#feca57" },
    { nazev: "Špagety", popis: "Boloňská omáčka, sýr.", cena: 130, barva: "#ff6b6b" },
    { nazev: "Salát", popis: "Caesar salát s kuřecím masem.", cena: 120, barva: "#1dd1a1" }
];

let seznamObjednavek = [];

// --- FUNKCE 1: Vypsání menu na obrazovku ---
function vygenerujMenu() {
    const kontejner = document.getElementById('menu-container');
    kontejner.innerHTML = ''; 

    menuData.forEach(jidlo => {
        const html = `
            <div class="karta-jidla">
                <div class="obrazek-jidla" style="background-color: ${jidlo.barva}"></div>
                <div class="info-jidla">
                    <h3>${jidlo.nazev}</h3>
                    <p>${jidlo.popis}</p>
                    <span class="cena">${jidlo.cena} Kč</span>
                    <button onclick="objednat('${jidlo.nazev}', ${jidlo.cena})">Objednat</button>
                </div>
            </div>
        `;
        kontejner.innerHTML += html;
    });
}

// --- FUNKCE 2: Přepínání sekcí (Menu / Objednávky / Statistiky) ---
function prepnoutSekci(idSekce) {

    const vsechnySekce = document.querySelectorAll('.sekce');
    vsechnySekce.forEach(sekce => {
        sekce.classList.add('hidden');
    });

    const vybranaSekce = document.getElementById(idSekce);
    if (vybranaSekce) {
        vybranaSekce.classList.remove('hidden');
    }
}

// --- FUNKCE 3: Kliknutí na tlačítko objednat ---
function objednat(nazev, cena) {

    seznamObjednavek.push({ nazev: nazev, cena: cena, stav: "Nová" });
    
    alert("Objednáno: " + nazev);
    
    aktualizujTabulku();
}

// --- FUNKCE 4: Vykreslení tabulky objednávek ---
function aktualizujTabulku() {
    const tabulka = document.getElementById('seznam-objednavek');
    tabulka.innerHTML = '';

    seznamObjednavek.forEach(obj => {
        const radek = `
            <tr>
                <td>${obj.nazev}</td>
                <td>${obj.cena} Kč</td>
                <td>${obj.stav}</td>
            </tr>
        `;
        tabulka.innerHTML += radek;
    });
}

vygenerujMenu();