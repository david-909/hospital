<?php
include_once("logic/functions.php");
startSession();
include_once("views/head.php");
$loginsvg = file_get_contents("assets/img/loginlogo.svg");
$deletesvg = file_get_contents("assets/img/deletebtn.svg");
?>

<?php
if (!isset($_SESSION["id_employee"])) {
?>

    <div class="login-container">

        <div class="login-to fdcolumn">
            <div class="login-view">
                
                <form id="loginForm">
                    <h2 class="title mt10 mb10">PRIJAVI SE</h2>
                    <div class="input-field mt10 mb10">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" placeholder="Korisničko ime" />
                    </div>
                    <div class="input-field br33">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" placeholder="Šifra" autocomplete="off" />
                    </div>
                    <input type="submit" id="loginButton" value="Uloguj se" class="btn-login br49 poppins mt25 mb30" />
                </form>
            </div>
            <span class="version">Current Version 1.0b</span>
        </div>

    </div>

    <!--     Fiskalna

    1. Presek stanja govori koliko ima kesa kartice ili ceka;


    Presek stanja
    1. Dugme ukljuci/iskljuci
    2. Na ekranu se pojavljuje registracija
    3. Strelicom na dole do izvestaja, pritisne se TOTAL
    4. Na ekranu ce pisati presek stanja i onda opet TOTAL

    Dnevni izvestaj
    1. -||-
    2. Na tastaturi broj 3 kliknuti
    3. Na ekranu ce pisati dnevni izvestaj
    4. Total

    

    Obavezno na kraj dana da se odradi presek

    Ukoliko zelite da promenite sifru u trenutnku
    1. unesite trenutni iznos npr 7000
    2. onda PRC
    3. Sifra npr 216
    4. PLU i tako za vise sifri pa tek sledeci korak
    5. Obavezno STL da proveris da li je dobro otkucano sta ste hteli
    6. TOTAL ako kesom, PY2 za karticu

    Periodicni izvestaj pocetak meseca za prosli mesec (fiskal)
    1. Ukljuci iskljuci
    2. Strelicom na dole do izvestaja
    3. TOTAL
    4. Strelicom na dole do periodicnog izvestaja
    5. TOTAL
    6. OD 010322 TOTAL
    7. DO 310322
    8. TOTAL

    

    

    

    


 -->



<?php
}
if (isset($_SESSION["id_employee"])) {
    include_once("views/nav.php");
    include_once("views/sidenav.php");
    include_once("views/acc-menu.php");
    include_once("views/footer.php");
?>

<?php
}
if (isset($_SESSION["id_role"]) and in_array($_SESSION["id_role"], [1, 2, 3, 4, 5])) {

?>
    <div class="behind-nav"></div>

    <div class="fnoalign container-index">

        <div class="left-container">
            <h2 class="gray fs20 txtc pt20 pb20 pl20 pr20">Fiskalni račun mora sadržati sve usluge koje su date pacijentu!!!</h2>
            <div class="acc-grid modal-w2">
                <div class="accordion1 js-accordion3 mr10 mt10">

                    <div class="accordion1__item js-accordion3-item">
                        <div class="accordion1-header js-accordion3-header fb ">
                            <span>Fiskalna kasa</span>
                        </div>
                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                            <div class="accordion1-body__contents fiskal-acc scroll2">
                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li class="txtc wght600 gray">Presek stanja govori koliko ima keša, kartice ili čeka</li>
                                        <li class="ml10 wght600 gray">Presek stanja</li>
                                        <li>1. Dugme uključi/isključi</li>
                                        <li>2. Na ekranu se pojavljuje registracija</li>
                                        <li>3. Strelicom na dole do izvestaja, onda TOTAL</li>
                                        <li>4. Na ekranu će pisati presek stanja, onda opet TOTAL</li>
                                    </ul>
                                </div>
                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li class="ml10 wght600 gray">Dnevni izveštaj</li>
                                        <li>1. Dugme uključi/isključi</li>
                                        <li>2. Na tastaturi broj 3 kliknuti</li>
                                        <li>3. Na ekranu će pisati dnevni izveštaj</li>
                                        <li>4. TOTAL</li>
                                    </ul>
                                </div>
                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li class="txtc wght600 gray">Periodični izveštaj početak meseca za prošli mesec (fiskal)</li>
                                        <li>1. Dugme uključi/isključi</li>
                                        <li>2. Strelicom na dole do izveštaja</li>
                                        <li>3. TOTAL</li>
                                        <li>4. Strelicom na dole do periodičnog izveštaja</li>
                                        <li>5. TOTAL</li>
                                        <li>6. OD 010322 TOTAL</li>
                                        <li>7. DO 310322</li>
                                        <li>8. TOTAL</li>
                                    </ul>
                                </div>
                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li class="txtc wght600 gray">Ukoliko želite da promenite šifru u trenutnku</li>
                                        <li>1. Unesite trenutni iznos npr 7000</li>
                                        <li>2. Onda PRC</li>
                                        <li>3. Šifra npr 216</li>
                                        <li>4. PLU i tako za vise šifri pa tek sledeći korak</li>
                                        <li>5. Obavezno STL da proveris da li je dobro otkucano sta ste hteli</li>
                                        <li>6. TOTAL ako kešom, PY2 za karticu</li>
                                    </ul>
                                </div>
                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li class="txtc wght600 gray">STORNO je WD</li>
                                        <li>Jednim klikom na WD storniraš samo poslednji artikal, a ako želite ceo raćun ond WD strelica</li>
                                        <li>Na gore pa opet WD i tako dok apart ne izbaci da nema više dalje</li>
                                        <li>pa nakon maximalnog korišćenja opcije WD pritisnite STL da proverite da li je račun na 0.</li>
                                        <li>Ako na ekranu stoji 0 onda pritisnite TOTAL.</li>
                                    </ul>





                                </div>


                                <div class="fiskalna-kasa">
                                    Aparat za kartice
                                    1. Pritisne se Kraj dana
                                    2. Šifra je 0000
                                    3. Zeleno dugme
                                </div>






                            </div>
                        </div>
                    </div>

                </div>

                <div class="accordion1 js-accordion3 mr10 mt10">

                    <div class="accordion1__item js-accordion3-item">
                        <div class="accordion1-header js-accordion3-header fb ">
                            <span>Trebovanje i ostale obaveze</span>
                        </div>
                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                            <div class="accordion1-body__contents fiskal-acc scroll2">



                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li>- Ugasiti muziku pred zatvaranja bolnice, ukoliko ima ležecih pacijenata smanjiti ili ugasiti u 19:00.</li>
                                        <li>- Molimo vas da ne zaboravite da se čekarite i odčekirate.</li>
                                        <li>- Pre nego što napsutite bolnicu, proverite sve klime i da li su vrata od izlaza na terasu zaključana.</li>
                                        <li>- Takođe pred kraj dana, ugasiti sve računare, pogasiti sva svetla i zaključati bolnicu.</li>
                                        <li>- Uraditi potrošnju na kraju dana kako bi imali uvid u tačno stanje.</li>
                                        <li>- Dopuniti torbe odmah nakon terena i razdužiti u svesku.</li>
                                        <li>- Trebovanje do 10 ujutro ako je GMS. Marica prva smena ili do 13h ako je GMS. Marica druga smena.</li>
                                        <li>- Trebovanje se vrši samo jednom tokom dana, a ukoliko zafali nešto od materijala neće biti dozvoljeno otvaranje centralne apoteke!</li>
                                        <li>- Kiseonik se trebuje kod Marije do 12:00 za sutradan.</li>
                                        <li>- Obavezno na kraj dana da se odradi presek stanja</li>
                                    </ul>

                                </div>






                            </div>
                        </div>
                    </div>

                </div>


                <div class="accordion1 js-accordion3 mr10 mt10">

                    <div class="accordion1__item js-accordion3-item">
                        <div class="accordion1-header js-accordion3-header fb ">
                            <span>Rendgen</span>
                        </div>
                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                            <div class="accordion1-body__contents fiskal-acc scroll2">



                                <div class="fiskalna-kasa">
                                    <ul>
                                        <li>1. Potrebno je nakon svakog korišćenja ugasiti laptop na shutdown!!!</li>
                                        <li>2. Takođe ga isključiti iz struje kako bi produžili vek trajanja aparata.</li>
                                        <li>3. Kad se isprazni panel, bateriju puniti 4h! Skinuti bateriju sa punjenja nakon 4h!</li>
                                        <li>4. Kad se prazni, prazni se do kraja jer ne treba se puniti dok je polu-puna.</li>
                                    </ul>

                                </div>






                            </div>
                        </div>
                    </div>

                </div>

                <div class="accordion1 js-accordion3 mr10 mt10">
                    <div class="accordion1__item js-accordion3-item active">
                        <div class="accordion1-header js-accordion3-header fb ">
                            <span>Šifrarnik</span>
                        </div>
                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                            <div class="accordion1-body__contents fiskal-acc scroll2">



                                <div class="sifrarnik">
                                    <ul>
                                        <li>
                                            <span>1</span>
                                            <p>Pregled lekara opste m</p><span>3300</span>
                                        </li>
                                        <li>
                                            <span>2</span>
                                            <p>EKG</p> <span>1000</span>
                                        </li>
                                        <li>
                                            <span>3</span>
                                            <p>Kucna poseta lekara</p><span>6000</span>
                                        </li>
                                        <li>
                                            <span>4</span>
                                            <p>Pregled lekara/noc ord.</p><span>6000</span>
                                        </li>
                                        <li>
                                            <span>5</span>
                                            <p>Kucna poseta lekara/noc</p><span>12000</span>
                                        </li>
                                        <li>
                                            <span>6</span>
                                            <p>Kucna poseta dr spec</p><span>12000</span>
                                        </li>
                                        <li>
                                            <span>7</span>
                                            <p>Pregled lekara spec</p><span>5000</span>
                                        </li>
                                        <li>
                                            <span>8</span>
                                            <p>Pregled opšteg hirurga</p><span>5000</span>
                                        </li>
                                        <li>
                                            <span>9</span>
                                            <p>Pregled kardiologa sa</p><span>5800</span>
                                        </li>
                                        <li>
                                            <span>10</span>
                                            <p> Pregled kardiologa sa</p><span>8800</span>
                                        </li>
                                        <li>
                                            <span>11</span>
                                            <p> Ultrazvuk srca sa Colo</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>12</span>
                                            <p> Pregled lekara spec.</p> <span>6000</span>
                                        </li>
                                        <li>
                                            <span>13</span>
                                            <p> Pregled lekara prim.</p> <span>12000</span>
                                        </li>
                                        <li>
                                            <span>14</span>
                                            <p> Pregled lekara prof.</p> <span>12000</span>
                                        </li>
                                        <li>
                                            <span>15</span>
                                            <p> Kontrolni pregled 1</p> <span>2000</span>
                                        </li>
                                        <li>
                                            <span>16</span>
                                            <p> Kontrolni pregled 2</p><span>3000</span>
                                        </li>
                                        <li>
                                            <span>17</span>
                                            <p> Kontrolni pregled 3</p> <span>6000</span>
                                        </li>
                                        <li>
                                            <span>18</span>
                                            <p> Pregled lekara prof.</p> <span>8000</span>
                                        </li>
                                        <li>
                                            <span>19</span>
                                            <p> Pregled kardiologa sa</p> <span>12000</span>
                                        </li>
                                        <li>
                                            <span>20</span>
                                            <p> UZ/Dopler srca</p> <span>6000</span>
                                        </li>
                                        <li>
                                            <span>21</span>
                                            <p> Holter pritiska</p> <span>7000</span>
                                        </li>
                                        <li>
                                            <span>22</span>
                                            <p> Holter EKG</p> <span>8000</span>
                                        </li>
                                        <li>
                                            <span>23</span>
                                            <p> RTG snimak</p> <span>3500</span>
                                        </li>
                                        <li>
                                            <span>24</span>
                                            <p> CD</p> <span>500</span>
                                        </li>
                                        <li>
                                            <span>25</span>
                                            <p> RTG snimak nocu</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>26</span>
                                            <p> Ultrazvuk abdomena</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>27</span>
                                            <p> Ultrazvuk dojki</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>28</span>
                                            <p> Ultrazvuk mekih tkiva/</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>29</span>
                                            <p> Ultrazvuk zgloba</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>30</span>
                                            <p> Ultrazvuk Ahilove teti</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>31</span>
                                            <p> Ultrazvuk skrotuma (te</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>32</span>
                                            <p> Ultrazvuk abdomena i s</p> <span>8000</span>
                                        </li>
                                        <li>
                                            <span>33</span>
                                            <p> Ultrazvuk abdomena sa</p> <span>8000</span>
                                        </li>
                                        <li>
                                            <span>34</span>
                                            <p> Ultrazvuk abdomena sa</p> <span>6000</span>
                                        </li>
                                        <li>
                                            <span>35</span>
                                            <p> Inhalacija/O2</p> <span>1400</span>
                                        </li>
                                        <li>
                                            <span>36</span>
                                            <p> Rastvor uz th. i.v.</p> <span>3000</span>
                                        </li>
                                        <li>
                                            <span>37</span>
                                            <p> Controloc/Lemod uz th. i.v.</p> <span>3000</span>
                                        </li>
                                        <li>
                                            <span>38</span>
                                            <p> Elfonis i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>39</span>
                                            <p> Cefim i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>40</span>
                                            <p> Marocen i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>41</span>
                                            <p> Vankomicin i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>42</span>
                                            <p> Merocid i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>43</span>
                                            <p> Invanz i.v.</p> <span>18000</span>
                                        </li>
                                        <li>
                                            <span>44</span>
                                            <p> Amikacin i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>45</span>
                                            <p> Levoxa i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>46</span>
                                            <p> Longaceph i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>47</span>
                                            <p> Fluconal i.v.</p> <span>3000</span>
                                        </li>
                                        <li>
                                            <span>48</span>
                                            <p> Fluconal i.v.</p> <span>5000</span>
                                        </li>
                                        <li>
                                            <span>49</span>
                                            <p> Orvagil i.v.</p> <span>3000</span>
                                        </li>
                                        <li>
                                            <span>50</span>
                                            <p> Orvagil/th i.v</p> <span>1000</span>
                                        </li>
                                        <li>
                                            <span>51</span>
                                            <p> Berlithion i.v. 1</p> <span>3000</span>
                                        </li>
                                        <li><span>52</span>
                                            <p>Berlithion i.v. 2</p><span>5000</span>
                                        </li>
                                        <li><span>53</span>
                                            <p>Intalipidi i.v.</p><span>5000</span>
                                        </li>
                                        <li><span>54</span>
                                            <p>Albumini i.v.</p><span>12000</span>
                                        </li>
                                        <li><span>55</span>
                                            <p>Ferrlecit i.v. 1</p><span>5000</span>
                                        </li>
                                        <li><span>56</span>
                                            <p>Ferrlecit i.v. 2</p><span>7000</span>
                                        </li>
                                        <li><span>57</span>
                                            <p>Ferrlecit i.v. 3</p><span>3000</span>
                                        </li>
                                        <li><span>58</span>
                                            <p>Fraxiparin 1</p><span>2500</span>
                                        </li>
                                        <li><span>59</span>
                                            <p>Fraxiparin 2</p><span>3500</span>
                                        </li>
                                        <li><span>60</span>
                                            <p>Fraxiparin 3</p><span>4500</span>
                                        </li>
                                        <li><span>61</span>
                                            <p>Paracetamol</p><span>1000</span>
                                        </li>
                                        <li><span>62</span>
                                            <p>Lek</p><span>10</span>
                                        </li>
                                        <li><span>63</span>
                                            <p>Lek</p><span>18</span>
                                        </li>
                                        <li><span>64</span>
                                            <p>Lek</p><span>20</span>
                                        </li>
                                        <li><span>65</span>
                                            <p>Lek</p><span>30</span>
                                        </li>
                                        <li><span>66</span>
                                            <p>Lek</p><span>40</span>
                                        </li>
                                        <li><span>67</span>
                                            <p>Lek</p><span>50</span>
                                        </li>
                                        <li><span>68</span>
                                            <p>Lek</p><span>100</span>
                                        </li>
                                        <li><span>69</span>
                                            <p>Lek</p><span>200</span>
                                        </li>
                                        <li><span>70</span>
                                            <p>Lek</p><span>300</span>
                                        </li>
                                        <li><span>71</span>
                                            <p>Lek</p><span>400</span>
                                        </li>
                                        <li><span>72</span>
                                            <p>Lek</p><span>500</span>
                                        </li>
                                        <li><span>73</span>
                                            <p>Lek</p><span>1000</span>
                                        </li>
                                        <li><span>74</span>
                                            <p>Lek</p><span>1500</span>
                                        </li>
                                        <li><span>75</span>
                                            <p>Lek</p><span>2000</span>
                                        </li>
                                        <li><span>76</span>
                                            <p>Lek</p><span>2500</span>
                                        </li>
                                        <li><span>77</span>
                                            <p>Infuzija 1</p><span>5000</span>
                                        </li>
                                        <li><span>78</span>
                                            <p>Infuzija 2</p><span>6000</span>
                                        </li>
                                        <li><span>79</span>
                                            <p>Infuzija 3</p><span>7000</span>
                                        </li>
                                        <li><span>80</span>
                                            <p>Infuzija 4</p><span>8000</span>
                                        </li>
                                        <li><span>81</span>
                                            <p>Infuzija 5</p><span>9000</span>
                                        </li>
                                        <li><span>82</span>
                                            <p>Infuzija 6</p><span>10000</span>
                                        </li>
                                        <li><span>83</span>
                                            <p>Infuzija 7</p><span>11000</span>
                                        </li>
                                        <li><span>84</span>
                                            <p>Infuzija 8</p><span>12000</span>
                                        </li>
                                        <li><span>85</span>
                                            <p>Infuzija 9</p><span>13000</span>
                                        </li>
                                        <li><span>86</span>
                                            <p>Infuzija 10</p><span>14000</span>
                                        </li>
                                        <li><span>87</span>
                                            <p>Infuzija 11</p><span>15000</span>
                                        </li>
                                        <li><span>88</span>
                                            <p>Infuzija 12</p><span>16000</span>
                                        </li>
                                        <li><span>89</span>
                                            <p>Infuzija 13</p><span>17000</span>
                                        </li>
                                        <li><span>90</span>
                                            <p>Infuzija 14</p><span>18000</span>
                                        </li>
                                        <li><span>91</span>
                                            <p>Infuzija 15</p><span>20000</span>
                                        </li>
                                        <li><span>92</span>
                                            <p>Infuzija Metilprednizo</p><span>7000</span>
                                        </li>
                                        <li><span>93</span>
                                            <p>Infuzija Zomete u boln</p><span>7800</span>
                                        </li>
                                        <li><span>94</span>
                                            <p>Kiseonicna terapija kr</p><span>3000</span>
                                        </li>
                                        <li><span>95</span>
                                            <p>Kiseonicna th 5l/24h</p><span>6000</span>
                                        </li>
                                        <li><span>96</span>
                                            <p>Davanje oralne terapij</p><span>1000</span>
                                        </li>
                                        <li><span>97</span>
                                            <p>Kiseonicna th >5l/24h</p><span>1600</span>
                                        </li>
                                        <li><span>98</span>
                                            <p>Davanje i.m. injekcija</p><span>1600</span>
                                        </li>
                                        <li><span>99</span>
                                            <p>Davanje i.m. injekcija</p><span>1000</span>
                                        </li>
                                        <li><span>100</span>
                                            <p>Davanje i.m. 2 pravca</p><span>2000</span>
                                        </li>
                                        <li><span>101</span>
                                            <p>Davanje i.m. Longaceph</p><span>2000</span>
                                        </li>
                                        <li><span>102</span>
                                            <p>Davanje i.m. Injekcije</p><span>3400</span>
                                        </li>
                                        <li><span>103</span>
                                            <p>Davanje i.m. Injekcije</p><span>4200</span>
                                        </li>
                                        <li><span>104</span>
                                            <p>Davanje i.v. Injekcije</p><span>1800</span>
                                        </li>
                                        <li><span>105</span>
                                            <p>I.v. Infuzija u bolnic</p><span>4000</span>
                                        </li>
                                        <li><span>106</span>
                                            <p>Davanje i.v. Injekcije</p><span>3400</span>
                                        </li>
                                        <li><span>107</span>
                                            <p>Infuzija na terenu po</p><span>5200</span>
                                        </li>
                                        <li><span>108</span>
                                            <p>Infuzija na terenu po</p><span>8600</span>
                                        </li>
                                        <li><span>109</span>
                                            <p>Zamena ili ispiranje k</p><span>5000</span>
                                        </li>
                                        <li><span>110</span>
                                            <p>Zamena ili ispiranje k</p><span>7000</span>
                                        </li>
                                        <li><span>111</span>
                                            <p>Previjanje</p><span>3800</span>
                                        </li>
                                        <li><span>112</span>
                                            <p>Previjanje malo</p><span>3000</span>
                                        </li>
                                        <li><span>113</span>
                                            <p>Previjanje srednje</p><span>4000</span>
                                        </li>
                                        <li><span>114</span>
                                            <p>Previjanje veliko</p><span>5000</span>
                                        </li>
                                        <li><span>115</span>
                                            <p>Previjanje na terenu s</p><span>5800</span>
                                        </li>
                                        <li><span>116</span>
                                            <p>Operacija ateroma loka</p><span>8000</span>
                                        </li>
                                        <li><span>117</span>
                                            <p>Operacija ateroma loka</p><span>10000</span>
                                        </li>
                                        <li><span>118</span>
                                            <p>Operacija ateroma loka</p><span>12000</span>
                                        </li>
                                        <li><span>119</span>
                                            <p>Operacija lipoma lokal</p><span>8000</span>
                                        </li>
                                        <li><span>120</span>
                                            <p>Operacija lipoma lokal</p><span>10000</span>
                                        </li>
                                        <li><span>121</span>
                                            <p>Operacija lipoma lokal</p><span>12000</span>
                                        </li>
                                        <li><span>122</span>
                                            <p>Skidanje mladeža u lok</p><span>10000</span>
                                        </li>
                                        <li><span>123</span>
                                            <p>Skidanje mladeža u lok</p><span>12000</span>
                                        </li>
                                        <li><span>124</span>
                                            <p>Skidanje mladeža u lok</p><span>15000</span>
                                        </li>
                                        <li><span>125</span>
                                            <p>Skidanje mladeža u lok</p><span>18000</span>
                                        </li>
                                        <li><span>126</span>
                                            <p>Skidanje mladeža u lok</p><span>20000</span>
                                        </li>
                                        <li><span>127</span>
                                            <p>Skidanje mladeža u lok</p><span>25000</span>
                                        </li>
                                        <li><span>128</span>
                                            <p>Skidanje bradavica do</p><span>3000</span>
                                        </li>
                                        <li><span>129</span>
                                            <p>Skidanje bradavica kom</p><span>500</span>
                                        </li>
                                        <li><span>130</span>
                                            <p>Skidanje bradavica akc</p><span>4000</span>
                                        </li>
                                        <li><span>131</span>
                                            <p>AT serum</p><span>3000</span>
                                        </li>
                                        <li><span>132</span>
                                            <p>AT vakcina</p><span>3000</span>
                                        </li>
                                        <li><span>133</span>
                                            <p>Operacija uraslog nokt</p><span>12000</span>
                                        </li>
                                        <li><span>134</span>
                                            <p>Operacija kurjeg oka</p><span>14400</span>
                                        </li>
                                        <li><span>135</span>
                                            <p>Klizma na terenu</p><span>5400</span>
                                        </li>
                                        <li><span>136</span>
                                            <p>Klizma na terenu posle</p><span>6600</span>
                                        </li>
                                        <li><span>137</span>
                                            <p>Spirometrija</p><span>4000</span>
                                        </li>
                                        <li><span>138</span>
                                            <p>Bronhodilatatorni</p><span>3000</span>
                                        </li>
                                        <li><span>139</span>
                                            <p>Bronhoprovokativni tes</p><span>12000</span>
                                        </li>
                                        <li><span>140</span>
                                            <p>Bronhoskopija u opštoj</p><span>39000</span>
                                        </li>
                                        <li><span>141</span>
                                            <p>HP nalaz</p><span>3500</span>
                                        </li>
                                        <li><span>142</span>
                                            <p>Sleep apnea u bolnici </p><span>36000</span>
                                        </li>
                                        <li><span>143</span>
                                            <p>Sleep apnea u kuci</p><span>30000</span>
                                        </li>
                                        <li><span>144</span>
                                            <p>Gastroskopija (EGDS) u</p><span>14000</span>
                                        </li>
                                        <li><span>145</span>
                                            <p>Gastroskopija (EGDS) u</p><span>22000</span>
                                        </li>
                                        <li><span>146</span>
                                            <p>Pregled nefrologa</p><span>5800</span>
                                        </li>
                                        <li><span>147</span>
                                            <p>Ultrazvuk bubrega i mo</p><span>5000</span>
                                        </li>
                                        <li><span>148</span>
                                            <p>Pregled nefrologa sa u</p><span>8000</span>
                                        </li>
                                        <li><span>149</span>
                                            <p>Intravenska pijelograf</p><span>16000</span>
                                        </li>
                                        <li><span>150</span>
                                            <p>Pregled endokrinologa </p><span>6000</span>
                                        </li>
                                        <li><span>151</span>
                                            <p>Ultrazvuk štitne žlezd</p><span>5000</span>
                                        </li>
                                        <li><span>152</span>
                                            <p>Pregled endokrinologa </p><span>8000</span>
                                        </li>
                                        <li><span>153</span>
                                            <p>Test opterecenja šecer</p><span>2000</span>
                                        </li>
                                        <li><span>154</span>
                                            <p>Test opterecenja šecer</p><span>3000</span>
                                        </li>
                                        <li><span>155</span>
                                            <p>Test opterecenja šecer</p><span>4000</span>
                                        </li>
                                        <li><span>156</span>
                                            <p>Test optereæenja šeæer</p><span>5000</span>
                                        </li>
                                        <li><span>157</span>
                                            <p>Pregled interniste hem</p><span>5800</span>
                                        </li>
                                        <li><span>158</span>
                                            <p>RTG snimak ramena</p><span>3800</span>
                                        </li>
                                        <li><span>159</span>
                                            <p>RTG snimak lakta</p><span>3800</span>
                                        </li>
                                        <li><span>160</span>
                                            <p>RTG snimak ruènog zglo</p><span>3800</span>
                                        </li>
                                        <li><span>161</span>
                                            <p>RTG snimak kolena (jed</p><span>3800</span>
                                        </li>
                                        <li><span>162</span>
                                            <p>RTG snimak skoènog zgl</p><span>3800</span>
                                        </li>
                                        <li><span>163</span>
                                            <p>Pregled vaskularnog hi</p><span>6000</span>
                                        </li>
                                        <li><span>164</span>
                                            <p>Pregled vaskularnog hi</p><span>8000</span>
                                        </li>
                                        <li><span>165</span>
                                            <p>Pregled vaskularnog hi</p><span>10000</span>
                                        </li>
                                        <li><span>166</span>
                                            <p>Dopler krvnih sudova o</p><span>5000</span>
                                        </li>
                                        <li><span>167</span>
                                            <p>Dopler krvnih sudova o</p><span>5000</span>
                                        </li>
                                        <li><span>168</span>
                                            <p>Dopler krvnih sudova v</p><span>5000</span>
                                        </li>
                                        <li><span>169</span>
                                            <p>Dopler abdominalne aor</p><span>5000</span>
                                        </li>
                                        <li><span>170</span>
                                            <p>Pregled urologa</p><span>5000</span>
                                        </li>
                                        <li><span>171</span>
                                            <p>Pregled urologa sa ult</p><span>8000</span>
                                        </li>
                                        <li><span>172</span>
                                            <p>Plasiranje silikonskog</p><span>3400</span>
                                        </li>
                                        <li><span>173</span>
                                            <p>Plasiranje silikonskog</p><span>4000</span>
                                        </li>
                                        <li><span>174</span>
                                            <p>Plasiranje silikonskog</p><span>12000</span>
                                        </li>
                                        <li><span>175</span>
                                            <p>Plasiranje silikonskog</p><span>6000</span>
                                        </li>
                                        <li><span>176</span>
                                            <p>Propiranje katetera u</p><span>12000</span>
                                        </li>
                                        <li><span>177</span>
                                            <p>Pregled specijaliste a</p><span>5000</span>
                                        </li>
                                        <li><span>178</span>
                                            <p>Terapija bola benigni</p><span>6000</span>
                                        </li>
                                        <li><span>179</span>
                                            <p>Terapija bola benigni</p><span>180000</span>
                                        </li>
                                        <li><span>180</span>
                                            <p>Brzi bris</p><span>2000</span>
                                        </li>
                                        <li><span>181</span>
                                            <p>Dvokrevetna i trokreve</p><span>18900</span>
                                        </li>
                                        <li><span>182</span>
                                            <p>Poluintezivna noc</p><span>24000</span>
                                        </li>
                                        <li><span>183</span>
                                            <p>Poluintezivna noc</p><span>32000</span>
                                        </li>
                                        <li><span>184</span>
                                            <p>Poluintezivna noc</p><span>26900</span>
                                        </li>
                                        <li><span>185</span>
                                            <p>Apartman jednokrevetni</p><span>26900</span>
                                        </li>
                                        <li><span>186</span>
                                            <p>Dnevna bolnica</p><span>17900</span>
                                        </li>
                                        <li><span>187</span>
                                            <p>Transplatacija kose (F</p><span>240000</span>
                                        </li>
                                        <li><span>188</span>
                                            <p>Transplatacija kose (F</p><span>264000</span>
                                        </li>
                                        <li><span>189</span>
                                            <p>Holter EKG-a</p><span>4000</span>
                                        </li>
                                        <li><span>190</span>
                                            <p>Holter pritiska</p><span>4000</span>
                                        </li>
                                        <li><span>191</span>
                                            <p>Kalijum u infuziji</p><span>4000</span>
                                        </li>
                                        <li><span>192</span>
                                            <p>Natrijum 10u infuziji</p><span>4000</span>
                                        </li>
                                        <li><span>193</span>
                                            <p>Lasix</p><span>50</span>
                                        </li>
                                        <li><span>194</span>
                                            <p>Vitamin C</p><span>50</span>
                                        </li>
                                        <li><span>195</span>
                                            <p>Bedoxin</p><span>30</span>
                                        </li>
                                        <li><span>196</span>
                                            <p>Beviplex</p><span>150</span>
                                        </li>
                                        <li><span>197</span>
                                            <p>Ketonal</p><span>50</span>
                                        </li>
                                        <li><span>198</span>
                                            <p>Natrijum 10u infuziji </p><span>80</span>
                                        </li>
                                        <li><span>199</span>
                                            <p>Kalijum</p><span>60</span>
                                        </li>
                                        <li><span>200</span>
                                            <p>Magnezijum</p><span>150</span>
                                        </li>
                                        <li><span>201</span>
                                            <p>Glukoza 50</p><span>350</span>
                                        </li>
                                        <li><span>202</span>
                                            <p>Diprophos</p><span>400</span>
                                        </li>
                                        <li><span>203</span>
                                            <p>Lidokain</p><span>22</span>
                                        </li>
                                        <li><span>204</span>
                                            <p>Longaceph</p><span>400</span>
                                        </li>
                                        <li><span>205</span>
                                            <p>Orvagil i.v.</p><span>200</span>
                                        </li>
                                        <li><span>206</span>
                                            <p>Paracetamol</p><span>250</span>
                                        </li>
                                        <li><span>207</span>
                                            <p>Kolonoskopija bez anes</p><span>20000</span>
                                        </li>
                                        <li><span>208</span>
                                            <p>Kolonoskopija sa anest</p><span>25000</span>
                                        </li>
                                        <li><span>209</span>
                                            <p>NaCl prazan uz vitamin</p><span>3000</span>
                                        </li>
                                        <li><span>210</span>
                                            <p>Tretman botoxa</p><span>23500</span>
                                        </li>
                                        <li><span>211</span>
                                            <p>Tretman hijaluronski f</p><span>23500</span>
                                        </li>
                                        <li><span>212</span>
                                            <p>Tretman PRP</p><span>17500</span>
                                        </li>
                                        <li><span>213</span>
                                            <p>Tretman u hiperbaricno</p><span>9400</span>
                                        </li>
                                        <li><span>214</span>
                                            <p>I.M. Injekcija Kenalog</p><span>1000</span>
                                        </li>
                                        <li><span>215</span>
                                            <p>I.M. Injekcija Decadu</p><span>2000</span>
                                        </li>
                                        <li><span>216</span>
                                            <p>Pregled lekara neurolo</p><span>6000</span>
                                        </li>
                                        <li><span>217</span>
                                            <p>Pregled profesora neur</p><span>8000</span>
                                        </li>
                                        <li><span>218</span>
                                            <p>Depozit za bolnicko le</p><span>120000</span>
                                        </li>
                                        <li><span>219</span>
                                            <p>Koncetrator O2 jednokratno</p><span>3000</span>
                                        </li>
                                        <li><span>220</span>
                                            <p>Bolnicko lecenje</p><span>1000</span>
                                        </li>
                                        <li><span>221</span>
                                            <p>Merenje glikemije</p><span>250</span>
                                        </li>
                                        <li><span>222</span>
                                            <p>Krvne analize</p><span>1000</span>
                                        </li>
                                        <li><span>223</span>
                                            <p>Post kovid paket</p><span>30000</span>
                                        </li>
                                        <li><span>224</span>
                                            <p>Dnevna terapija</p><span>5000</span>
                                        </li>
                                    </ul>

                                </div>






                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="msg-box">

        </div>


        <!-- <div class="contact-popup hidden-xs" style="display: none;">
            <div class="need-help-wrapper">
                <div class="need-help-header" onClick="gtag('event', 'Clicked Need Help', {'event_category': 'Contact'});">
                    Need Help?</div>
                <div class="need-help-content"><span class="need-help-lines-open">Phone Lines Are Open!</span><span class="need-help-phone-no">00000 000000</span></div>
            </div>
        </div> -->
    </div>

<?php
}
?>
<div id="notifPasswordChange"></div>
<?php
include_once("views/scripts.php");
?>