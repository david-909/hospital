$(function () {
  const TIME_TO_LOGOUT = 3600000;
  var timeout;
  document.onmousemove = function () {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      $.ajax({
        type: "POST",
        url: "logic/logout.php",
        data: {},
        dataType: "json",
        success: function (response) {
          response ? window.location.replace("index.php") : "";
        },
      });
    }, TIME_TO_LOGOUT);
  };
  const CURRENT_YEAR = +new Date().getFullYear();
  const NAMES_REGEX = /^[A-ZŠĐŽĆČ][a-zšđžćč]+$/;
  const JMBG_REGEX = /^[0-9]{13}$/;
  const IDENTIFICATION_NUMBER_REGEX = /^[0-9]{9}$/;
  const EMAIL_REGEX =
    /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
  const PHONE_REGEX = /^[+][3][8][0-9][\d]+$/;
  const ADDRESS_REGEX = /^[A-zŠĐČĆŽžđšćč0-9\s\.]+$/;
  const ERROR_NAMES = "*Mora početi velikim slovom!";
  const JMBG_ERROR = "*JMBG mora imati 13 cifara!";
  const IDENTIFICATION_NUMBER_ERROR = "*Br.lične mora imati 9 cifara!";
  const EMAIL_ERROR = "Email nije u dobrom formatu!";
  const DATE_ERROR_YEAR = "*Datum mora biti validan!";
  const PHONE_ERROR = "*Mora početi sa +38xxx!";
  const ADDRESS_ERROR = "*Obavezno polje!";
  var bloodTotal = 0;
  var appointmentTotal = 0;
  var idMemoryValue = 0;
  var previousIdTherapy;
  var idTherapyType = document.createElement("input");
  var idChosenTherapyId = document.createElement("input");
  idTherapyType.setAttribute("type", "hidden");
  idChosenTherapyId.setAttribute("type", "hidden");
  idTherapyType.setAttribute("id", "idTherapyType");
  idChosenTherapyId.setAttribute("id", "idChosenTherapyId");
  document.querySelector("body").append(idTherapyType);
  $(document).on("click", "#loginButton", function (e) {
    e.preventDefault();
    var username = $("#username").val();
    var password = $("#password").val();
    $.ajax({
      type: "POST",
      url: "logic/login.php",
      data: {
        username: username,
        password: password,
      },
      dataType: "json",
      success: function (response) {
        if (response == 1) {
          window.location.reload();
        } else {
          toast(
            "Pogrešno Korisničko ime ili Šifra. Pokušajte ponovo.",
            "Greška",
            "error"
          );
        }
      },
    });
  });
  $(document).on("click", "#updatePatientButton", function (e) {
    e.preventDefault();
    var errors = [];
    var idPatient = +$("#nameUpdate").attr("data-id");
    var nameUpdate = $("#nameUpdate").val();
    var surnameUpdate = $("#surnameUpdate").val();
    var parentNameUpdate = $("#parentNameUpdate").val();
    var emailUpdate = $("#emailUpdate").val();
    var phoneUpdate = $("#phoneUpdate").val();
    var jmbgUpdate = $("#jmbgUpdate").val();
    var dateOfBirthUpdate = $("#dateOfBirthUpdate").val();
    var identificationNumberUpdate = $("#identificationNumberUpdate").val();
    var addressUpdate = $("#addressUpdate").val();
    var commentPatientUpdate = $("#commentPatientUpdate").val();
    var yearToCheck =
      dateOfBirthUpdate.substring(0, dateOfBirthUpdate.length - 6) * 1;
    var nameErrorUpdate = $("#nameErrorUpdate");
    var dateErrorUpdate = $("#dateErrorUpdate");
    var surnameErrorUpdate = $("#surnameErrorUpdate");
    var parentNameErrorUpdate = $("#parentNameErrorUpdate");
    var emailErrorUpdate = $("#emailErrorUpdate");
    var phoneErrorUpdate = $("#phoneErrorUpdate");
    var jmbgErrorUpdate = $("#jmbgErrorUpdate");
    var identificationNumberErrorUpdate = $("#identificationNumberErrorUpdate");
    var addressErrorUpdate = $("#addressErrorUpdate");
    nameErrorUpdate.html("");
    dateErrorUpdate.html("");
    surnameErrorUpdate.html("");
    parentNameErrorUpdate.html("");
    emailErrorUpdate.html("");
    jmbgErrorUpdate.html("");
    identificationNumberErrorUpdate.html("");
    addressErrorUpdate.html("");
    phoneErrorUpdate.html("");
    if (yearToCheck <= 1900 || yearToCheck > CURRENT_YEAR) {
      errors.push(DATE_ERROR_YEAR);
      dateErrorUpdate.html(DATE_ERROR_YEAR);
    }
    if (errors.length == 0) {
      $.ajax({
        type: "POST",
        url: "logic/updatePatient.php",
        data: {
          id: idPatient,
          name: nameUpdate,
          surname: surnameUpdate,
          parentName: parentNameUpdate,
          email: emailUpdate,
          phone: phoneUpdate,
          jmbg: jmbgUpdate,
          identificationNumber: identificationNumberUpdate,
          address: addressUpdate,
          dateOfBirth: dateOfBirthUpdate,
          commentPatient: commentPatientUpdate,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            window.location.reload();
            setTimeout(() => {
              toast("Uspešno promenjeno.", "Uspešno", "success");
            }, 500);
          } else if (response == 0) {
            toast("Došlo je do greške", "Greška", "error");
          }
        },
      });
    }
  });
  $("#logoutButton").click(function (e) {
    e.preventDefault();
    var id_employee = $("#id_employee").val();
    $.ajax({
      type: "POST",
      url: "logic/logout.php",
      data: {
        id_employee: id_employee,
      },
      dataType: "json",
      success: function (response) {
        window.location.replace("index.php");
      },
    });
  });
  $(".buttonForTables").click(function (e) {
    e.preventDefault();
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var id = +$(this).attr("id");
    var page = 1;
    $.ajax({
      type: "GET",
      url: "logic/viewTables.php",
      data: {
        page: page,
        id: id,
        pageLocation: pageLocation
      },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  $(".searches").click(function (e) {
    e.preventDefault();
    $(".buttonForTables").removeClass("activecena");
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var id = +$(this).prev().attr("id");
    $(this).prev().addClass("activecena");
    var page = 1;
    $.ajax({
      type: "GET",
      url: "logic/viewTables.php",
      data: {
        page: page,
        id: id,
        pageLocation: pageLocation
      },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  $("#passwordChangeButton").on("click", function (e) {
    e.preventDefault();
    var passwordTrenutno = $("#passwordChangeNow").val();
    var password1 = $("#passwordChange").val();
    var password2 = $("#passwordChangeConf").val();
    if (password1 == password2 && password1 != "") {
      $.ajax({
        type: "POST",
        url: "logic/changePassword.php",
        data: {
          password: passwordTrenutno,
          passwordChange: password1,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            toast("Šifra uspešno promenjena.", "Uspešno", "success");
          } else if (response == 2) {
            toast("Pogrešna trenutna šifra.", "Greška", "error");
          } else if (response == 0) {
            toast("Došlo je do greške, pokušajte ponovo.", "Greška", "error");
          }
        },
      });
    } else {
      toast("Šifre se ne poklapaju, pokušajte ponovo.", "Greška", "error");
    }
  });
  $("#addPatientButton").click(function (e) {
    e.preventDefault();
    var errors = [];
    var name = $("#name").val();
    var surname = $("#surname").val();
    var parentName = $("#parentName").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var jmbg = $("#jmbg").val();
    var dateOfBirth = $("#dateOfBirth").val();
    var identificationNumber = $("#identificationNumber").val();
    var address = $("#address").val();
    var commentPatient = $("#commentPatient").val();
    ////
    var nameError = $("#nameError");
    var dateError = $("#dateError");
    var surnameError = $("#surnameError");
    var parentNameError = $("#parentNameError");
    var emailError = $("#emailError");
    var phoneError = $("#phoneError");
    var jmbgError = $("#jmbgError");
    var identificationNumberError = $("#identificationNumberError");
    var addressError = $("#addressError");
    nameError.html("");
    dateError.html("");
    surnameError.html("");
    parentNameError.html("");
    emailError.html("");
    jmbgError.html("");
    identificationNumberError.html("");
    addressError.html("");
    phoneError.html("");
    ////
    var yearToCheck = dateOfBirth.substring(0, dateOfBirth.length - 6) * 1;
    checkRegex(name, NAMES_REGEX, ERROR_NAMES, nameError, errors);
    checkRegex(surname, NAMES_REGEX, ERROR_NAMES, surnameError, errors);
    checkRegex(parentName, NAMES_REGEX, ERROR_NAMES, parentNameError, errors);
    email != ""
      ? checkRegex(email, EMAIL_REGEX, EMAIL_ERROR, emailError, errors)
      : emailError.html("");
    checkRegex(phone, PHONE_REGEX, PHONE_ERROR, phoneError, errors);
    checkRegex(jmbg, JMBG_REGEX, JMBG_ERROR, jmbgError, errors);
    checkRegex(address, ADDRESS_REGEX, ADDRESS_ERROR, addressError, errors);
    checkRegex(
      identificationNumber,
      IDENTIFICATION_NUMBER_REGEX,
      IDENTIFICATION_NUMBER_ERROR,
      identificationNumberError,
      errors
    );
    if (
      yearToCheck <= 1900 ||
      yearToCheck > CURRENT_YEAR ||
      isFutureDate(dateOfBirth)
    ) {
      errors.push(DATE_ERROR_YEAR);
      dateError.html(DATE_ERROR_YEAR);
    }
    if (errors.length == 0) {
      $.ajax({
        type: "POST",
        url: "logic/insertPatient.php",
        data: {
          name: name,
          surname: surname,
          parentName: parentName,
          email: email,
          phone: phone,
          jmbg: jmbg,
          identificationNumber: identificationNumber,
          address: address,
          dateOfBirth: dateOfBirth,
          commentPatient: commentPatient,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            toast("Uspešan unos u bazu", "Uspešno", "success");
          } else if (response == 0) {
            toast("Neuspešan unos", "Greška", "error");
            windowlocation.reload();
          } else if (response == 2) {
            toast("Već postoji pacijent sa istim JMBGom.", "Greška", "error");
          }
        },
      });
    } else {
    }
  });
  $("#searchNameSurname").on("input", function () {
    var search = $("#searchNameSurname").val();
    var searchOutput = document.getElementById("searchOutput");
    searchOutput.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/search.php",
      data: {
        search: search,
      },
      dataType: "json",
      success: function (response) {
        for (let i = 0; i < response.length; i++) {
          searchOutput.innerHTML += `<button class='chosenPatient search-btn btn br33' data-id='${response[i]["id_patient"]}'>${response[i]["name"]} ${response[i]["surname"]}&nbsp;&nbsp|&nbsp;&nbsp${response[i]["jmbg"]}</button>`;
        }
      },
    });
  });
  

  // !OVDE SAM DODAO DA GASI MODAL1 --------------------------
  $(document).on("click", "#exitAccount2", function (e) {
    e.preventDefault();
    window.location.replace("kartoni.php");
  });
  // !--------------------------------------------------------
  $("#searchNameSurnameCardboard").on("input", function () {
    var search = $("#searchNameSurnameCardboard").val();
    var searchOutputCardboard = document.getElementById(
      "searchOutputCardboard"
    );
    $.ajax({
      type: "POST",
      url: "logic/search.php",
      data: {
        search: search,
      },
      dataType: "json",
      success: function (response) {
        searchOutputCardboard.innerHTML = "";
        for (let i = 0; i < response.length; i++) {
          searchOutputCardboard.innerHTML += `<button class='chosenPatientCardboard search-btn btn br33' data-modal-target="#modal1"  data-id='${response[i]["id_patient"]}'>${response[i]["name"]} ${response[i]["surname"]}&nbsp;&nbsp;|&nbsp;&nbsp;${response[i]["jmbg"]}</button>`;
        }
      },
    });
  });
  $(document).on("click", ".chosenPatientCardboard", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.location.replace(`kartoni.php?id=${id}`);
  });
  $(document).on("click", "#customTherapy", addRemoveButton);
  $(document).on("click", ".chosenTherapy", addRemoveButton);
  $(document).on("blur", "#searchNameSurname", function () {
    setTimeout(() => {
      searchOutput.innerHTML = "";
    }, 1000);
  });
  $(document).on("blur", "#searchNameSurnameCardboard", function () {
    setTimeout(() => {
      document.getElementById("searchOutputCardboard").innerHTML = "";
    }, 300);
  });
  $(document).on("click", ".chosenPatient", function (e) {
    e.preventDefault();
    var idPatient = $(this).attr("data-id");
    var nameSurname = $(this).text();
    nameSurname = nameSurname.substring(0, nameSurname.length - 18);
    var searchNameSurname = document.getElementById("searchNameSurname");
    searchOutput.innerHTML = "";
    searchNameSurname.setAttribute("data-id", idPatient);
    searchNameSurname.value = nameSurname;
  });
  $(document).on("input", "#searchBlood", function () {
    var searchBlood = $("#searchBlood").val();
    var searchBloodResult = document.getElementById("searchBloodResult");
    var blood = document.getElementById("blood");
    searchBloodResult.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchBlood.php",
      data: {
        searchBlood: searchBlood,
      },
      dataType: "json",
      success: function (response) {
        blood.innerHTML = "";
        for (let i = 0; i < response.length; i++) {
          blood.innerHTML += `<option class='chosenBlood select-list1-item' data-id='${response[i]["id_blood"]}' data-price='${response[i]["price"]}'>${response[i]["analysis"]}</option>`;
        }
      },
    });
  });
  $("#moveToFinalBloods").on("click", function (e) {
    e.preventDefault();
    var finalBloodsSelect = document.getElementById("finalBloods");
    var finalBloodsSelectedOptions = Array.from(
      document.querySelectorAll("#finalBloods option")
    );
    var finalBloodsSelectedOptionsIds = finalBloodsSelectedOptions.map(
      (x) => +x.getAttribute("data-id")
    );
    finalBloodsSelect.setAttribute("size", 11);
    var selectedBloods = Array.from($(".chosenBlood:selected"));
    var totalPriceBloods = document.getElementById("totalPriceBloods");
    bloodTotal = Number.parseInt(totalPriceBloods.innerText);
    var selectedBloodsIds = [];
    for (let i = 0; i < selectedBloods.length; i++) {
      selectedBloodsIds.push(
        Number.parseInt($(selectedBloods[i]).attr("data-id"))
      );
      bloodTotal += Number.parseInt($(selectedBloods[i]).attr("data-price"));
    }
    var isAlreadyThere = selectedBloodsIds.some((x) =>
      finalBloodsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedBloods.length; i++) {
      if (!isAlreadyThere) {
        finalBloodsSelect.innerHTML += `<option class="removeBlood select-list1-item" data-id='${$(
          selectedBloods[i]
        ).attr("data-id")}' data-price='${$(selectedBloods[i]).attr(
          "data-price"
        )}'>${selectedBloods[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Vec postoji duplikat krvi.", "Greška", "error")
      : (totalPriceBloods.innerText = bloodTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
  });
  $("#moveFromFinalBloods").click(function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById("totalPriceBloods");
    var finalBloodsSelect = document.getElementById("finalBloods");
    var selectedBloodsFinalAll = Array.from($("#finalBloods option"));
    var selectedBloodsFinal = Array.from($("#finalBloods option:selected"));
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloods"),
      document.getElementById("totalPriceAppointments"),
      document.getElementById("totalPriceMedicines"),
      document.getElementById("totalPriceAccount")
    );
  });
  $(document).on("click", "#moveToFinalAppointments", function (e) {
    e.preventDefault();
    var finalAppointmentsSelect = document.getElementById("finalAppointments");
    finalAppointmentsSelect.setAttribute("size", 5);
    var selectedAppointments = Array.from($(".chosenAppointment:selected"));
    var totalPriceAppointments = document.getElementById(
      "totalPriceAppointments"
    );
    appointmentTotal = Number.parseInt(totalPriceAppointments.innerText);
    var selectedAppointmentsIds = [];
    var finalAppointmetsSelectedOptionsIds = Array.from(
      document.querySelectorAll("#finalAppointments option")
    ).map((x) => +x.getAttribute("data-id"));
    for (let i = 0; i < selectedAppointments.length; i++) {
      selectedAppointmentsIds.push(
        Number.parseInt($(selectedAppointments[i]).attr("data-id"))
      );
      appointmentTotal += Number.parseInt(
        $(selectedAppointments[i]).attr("data-price")
      );
    }
    var isAlreadyThere = selectedAppointmentsIds.some((x) =>
      finalAppointmetsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedAppointments.length; i++) {
      if (!isAlreadyThere) {
        finalAppointmentsSelect.innerHTML += `<option class='removeAppointment select-list1-item' data-id='${$(
          selectedAppointments[i]
        ).attr("data-id")}' data-price='${$(selectedAppointments[i]).attr(
          "data-price"
        )}'>${selectedAppointments[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Vec postoji duplikat pregleda.", "Greška", "error")
      : (totalPriceAppointments.innerText = appointmentTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
  });
  $(document).on("click", "#moveFromFinalAppointments", function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById("totalPriceAppointments");
    var finalBloodsSelect = document.getElementById("finalAppointments");
    var selectedBloodsFinalAll = Array.from($("#finalAppointments option"));
    var selectedBloodsFinal = Array.from(
      $("#finalAppointments option:selected")
    );
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloods"),
      document.getElementById("totalPriceAppointments"),
      document.getElementById("totalPriceMedicines"),
      document.getElementById("totalPriceAccount")
    );
  });
  $(document).on("change", ".medicines", function () {
    var medicineId = $(this).attr("data-id");
    var medicineQuantity = $(this).val();
    var medicinePrice = Number.parseInt($(this).attr("data-priceMedicine"));
    var medicinePriceSpan = $(this).next();
    medicinePriceSpan.html(medicinePrice * medicineQuantity);
    var totalPriceMedicinesSpan = $("#totalPriceMedicines");
  });
  $(document).on("click", ".chosenTherapy", function (e) {
    e.preventDefault();
    document.querySelector("body").append(idChosenTherapyId);
    idTherapyType.setAttribute("value", "1");
    var totalPriceMedicinesSpan = document.getElementById(
      "totalPriceMedicines"
    );
    $("#medicines1").html("");
    var totalPriceMedicines = 0;
    var idTherapy = $(this).attr("data-id");
    idMemoryValue = idTherapy;
    idChosenTherapyId.value = idMemoryValue;
    var medicinesDiv = document.getElementById("medicines");
    if (idTherapy != previousIdTherapy) {
      $.ajax({
        type: "POST",
        url: "logic/getMedicines.php",
        data: {
          idTherapy: idTherapy,
        },
        dataType: "json",
        success: function (r) {
          previousIdTherapy = r[0]["id_therapy"];
          var output = ``;
          for (let i = 0; i < r.length; i++) {
            output += `<div class='single fl'><label class='fl scroll2'>${
              r[i]["medicine"]
            }</label><input class='single-input medicines' type='number' min='0' readonly default='${
              r[i]["tmQuantity"]
            }' class='medicines checkIt single-input' data-idMedicine='${
              r[i]["id_medicine"]
            }' value='${r[i]["tmQuantity"]}' class='single-input' data-priceMedicine='${
              r[i]["price"]
            }'/><span class='medicinePrice fc' id='${r[i]["id_medicine"]}'>${
              r[i]["price"] * r[i]["tmQuantity"]
            }</span></div>`;
          }
          medicinesDiv.innerHTML = output;
          var arrayOfMedicinePrices = Array.from(
            document.querySelectorAll(".medicinePrice")
          );
          var finalArrayOfMedicinePrices = arrayOfMedicinePrices.map((x) =>
            Number.parseInt(x.innerText)
          );
          totalPriceMedicines = calculateSum(finalArrayOfMedicinePrices);
          totalPriceMedicinesSpan.innerText = totalPriceMedicines;
          calculateTotalPrice(
            document.getElementById("totalPriceBloods"),
            document.getElementById("totalPriceAppointments"),
            document.getElementById("totalPriceMedicines"),
            document.getElementById("totalPriceAccount")
          );
        },
      });
    } else {
      medicinesDiv.innerHTML = "";
      $.ajax({
        type: "POST",
        url: "logic/getMedicines.php",
        data: {
          idTherapy: idTherapy,
        },
        dataType: "json",
        success: function (r) {
          var output = ``;
          for (let i = 0; i < r.length; i++) {
            output += `<div class='single fl'><label class='fl scroll2'>${
              r[i]["medicine"]
            }</label><input type='number' default='${
              r[i]["tmQuantity"]
            }' min='0' class='medicines checkIt single-input' data-id='${
              r[i]["id_medicine"]
            }' value='${r[i]["tmQuantity"]}' class='single-input medicines' data-priceMedicine='${
              r[i]["price"]
            }'/><span class='medicinePrice fc' id='${r[i]["id_medicine"]}'>${
              r[i]["price"] * r[i]["tmQuantity"]
            }</span></div>`;
          }
          medicinesDiv.innerHTML = output;
          var arrayOfMedicinePrices = Array.from(
            document.querySelectorAll(".medicinePrice")
          );
          var finalArrayOfMedicinePrices = arrayOfMedicinePrices.map((x) =>
            Number.parseInt(x.innerText)
          );
          totalPriceMedicines = calculateSum(finalArrayOfMedicinePrices);
          totalPriceMedicinesSpan.innerText = totalPriceMedicines;
        },
      });
    }
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
    }, 100);
  });
 /*  $(document).on("change", ".medicines", function (e) {
    e.preventDefault();
    var medicineId = $(this).attr("data-id");
    var medicineQuantity = $(this).val();
    var medicinePrice = Number.parseInt($(this).attr("data-priceMedicine"));
    var medicinePriceSpan = $(this).next();
    medicinePriceSpan.html(medicinePrice * medicineQuantity);
    var total = 0;
    var prices = Array.from(document.querySelectorAll(".medicinePrice"));
    var newArray = prices.map((x) => Number.parseInt(x.innerText));
    var total = calculateSum(newArray);
    $("#totalPriceMedicines").text(total);
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  }); */
  $(document).on("click", "#customTherapy", function (e) {
    e.preventDefault();
    $("#idChosenTherapyId").remove();
    idTherapyType.setAttribute("value", "2");
    var topMedicines = document.getElementById("medicines");
    var totalPriceMedicines = document.getElementById("totalPriceMedicines");
    var divForDelete = document.getElementById("medicines1");
    
    divForDelete != null || divForDelete != undefined
      ? (divForDelete.innerHTML = "")
      : "";
    totalPriceMedicines.innerText = 0;
    topMedicines.innerHTML = "";
    topMedicines.innerHTML += `<div class=''><div class='search-component modal-s fc sc00'><span class="fc"><svg id="search" xmlns="http://www.w3.org/2000/svg" width="682.667" height="682.667" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet"><path d="M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z"/></svg></span>
    <input class='search-input ' type="text" name="searchMedicine" id="searchMedicine" placeholder="Pretraži lek..."></div>
    <div class="medicineOutputClassFront scroll2" id="medicineOutput"></div>
    <label for='startingTherapy' class='fc fs16 txtc wght600 gray'>Počni od</label><div class='fc mb10' id='startingTherapy'></div>
</div><div class="fc">
<div class="medicinesClass" id="medicines1">
</div></div>`;
    const r = async () => {
      var print = "";
      $.ajax({
        type: "GET",
        url: "logic/getTherapies.php",
        data: {},
        dataType: "json",
        success: await function (response) {
          print += ``;
          response.forEach((r) => {
            print += `<button class='btn startingTherapy' data-idTherapy='${r["id_therapy"]}'>${r["therapy"]}</button>`;
          });
          $("#startingTherapy").html(print);
        },
      });
      
    };
    r().then(() => {
      $.ajax({
        type: "POST",
        url: "logic/getTopMedicine.php",
        data: {},
        dataType: "json",
        success: function (response) {
          topMedicines.innerHTML += "<div class='fnoalign apotekaflex'><div class='fdcolumn2 materijal-med'><p class='fs20 gray wght600 txtc'>Potrošni materijal</p><div class='potrosni-materijal scroll2'></div></div><div class='fdcolumn2 materijal-med'><p class='fs20 gray wght600 txtc'>Favoriti</p><div class='fav-medicines scroll2'></div></div></div><div class='medicineShortcut' id='medicineShortcut'></div>";
          var print = "";
          for (let i = 0; i < response.length; i++) {
            print += `<div class='single fl'><label class='fl scroll2' for="${response[i]["medicine"]}">${response[i]["medicine"]}</label><input type="number" data-priceMedicine='${response[i]["price"]}' class='mostUsedMedicines medicines' data-idMedicine='${response[i]["id_medicine"]}' name='${response[i]["medicine"]}' min='0'><span data-price='${response[i]["price"]}' class="priceOfMedicineCustom fc">0</span><button class='xxx fc'><svg id="minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z"/></svg></button></div>  `;
          }
          $("#medicineShortcut").html(print);
          $( ".medicineShortcut .single:nth-child(-n+16)" ).prependTo('.potrosni-materijal');
          $(".medicineShortcut .single").slice(0,999).prependTo('.fav-medicines');
        },
      });
    });
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
    }, 100);
  });

  $(document).on("click", ".startingTherapy", function (e) {
    e.preventDefault();
    var idTherapy = +this.getAttribute("data-idTherapy");
    $.ajax({
      type: "POST",
      url: "logic/getMedicines.php",
      data: { idTherapy: idTherapy },
      dataType: "json",
      success: function (response) {
        document.getElementById("medicines1").innerHTML = "";
        document.getElementById("medicineShortcut").innerHTML = "";
        for (let i = 0; i < response.length; i++) {
          var total = response[i]["tmQuantity"] * response[i]["price"];
          document.getElementById(
            "medicines1"
          ).innerHTML += `<div class='single fl'><label class='fl scroll2' for="${response[i]["medicine"]}">${response[i]["medicine"]}</label><input type="number" data-priceMedicine='${response[i]["price"]}' class='mostUsedMedicines medicines' data-idMedicine='${response[i]["medicineId"]}' name='${response[i]["medicine"]}' min='0' value='${response[i]["tmQuantity"]}'><span data-price='${response[i]["price"]}' class="priceOfMedicineCustom fc">${total}</span><button class='xxx fc'><svg id="minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z"/></svg></button></div>`;
        }
        var totalPrice = Array.from(
          document.querySelectorAll(".priceOfMedicineCustom")
        );
        var mappedArray = totalPrice.map((x) => x.innerText * 1);
        var finalPrice = calculateSum(mappedArray);
        document.getElementById("totalPriceMedicines").innerText = finalPrice;
        calculateTotalPrice(
          document.getElementById("totalPriceBloods"),
          document.getElementById("totalPriceAppointments"),
          document.getElementById("totalPriceMedicines"),
          document.getElementById("totalPriceAccount")
        );
      },
    });
  });
  
  $(document).on("click", ".startingTherapy1", function (e) {
    e.preventDefault();
    var idTherapy = +this.getAttribute("data-idTherapy");
    document.getElementById("topMed").innerHTML = "";
    //document.getElementById("medicinesSubAccount").innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/getMedicines.php",
      data: { idTherapy: idTherapy },
      dataType: "json",
      success: function (response) {
        document.getElementById("medicines1").innerHTML = "";
        document.getElementById("topMed").innerHTML = "";

        for (let i = 0; i < response.length; i++) {
          var total = response[i]["tmQuantity"] * response[i]["price"];
          document.getElementById(
            /* "medicinesSubAccount", */
            "topMed"
          ).innerHTML += `<div class='single fl'><label class='fl scroll2' for="${response[i]["medicine"]}">${response[i]["medicine"]}</label><input type="number" data-priceMedicine='${response[i]["price"]}' class='mostUsedMedicines1 medicines' data-idMedicine='${response[i]["medicineId"]}' name='${response[i]["medicine"]}' min='1' value='${response[i]["tmQuantity"]}'><span data-price='${response[i]["price"]}' class="priceOfMedicineCustom1 fc">${total}</span><button class='xxx1 fc'><svg id="minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z"/></svg></button></div>`;
        }
        var totalPrice = Array.from(
          document.querySelectorAll(".priceOfMedicineCustom1")
        );
        var mappedArray = totalPrice.map((x) => x.innerText * 1);
        var finalPrice = calculateSum(mappedArray);
        document.getElementById("totalPriceTherapySubAccount").innerText =
          finalPrice;
        calculateTotalPrice(
          document.getElementById("totalPriceBloodsSubAccount"),
          document.getElementById("totalPriceAppointmentsSubAccount"),
          document.getElementById("totalPriceTherapySubAccount"),
          document.getElementById("totalPriceSubAccount")
        );
      },
    });
  });
  $(document).on("click", ".xxx", function (e) {
    e.preventDefault();
    var price = +$(this).prev().text();
    var totalPriceMedicines = document.getElementById("totalPriceMedicines");
    /* $(this).next().remove();
    $(this).prev().remove();
    $(this).prev().remove();
    $(this).prev().remove();
    $(this).remove(); */
    var input = $(this).prev().prev();
    input.val("");
    $(this).prev().text(0);
    /* input.value = ""; */
    totalPriceMedicines.innerText = +totalPriceMedicines.innerText - price;
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
    }, 100);
  });
  $(document).on("change", ".mostUsedMedicines", function () {
    var quantity = $(this).val();
    var medicinePriceSpan = $(this).next();
    var price = $(this).next().attr("data-price") * 1;
    medicinePriceSpan.html(quantity * price);
    var totalPrice = Array.from(
      document.querySelectorAll(".priceOfMedicineCustom")
    );
    var mappedArray = totalPrice.map((x) => x.innerText * 1);
    var finalPrice = calculateSum(mappedArray);
    document.getElementById("totalPriceMedicines").innerText = finalPrice;
    calculateTotalPrice(
      document.getElementById("totalPriceBloods"),
      document.getElementById("totalPriceAppointments"),
      document.getElementById("totalPriceMedicines"),
      document.getElementById("totalPriceAccount")
    );
  });
  $(document).on("input", "#searchMedicine", function () {
    var searchMedicine = $("#searchMedicine").val();
    var medicineOutput = document.getElementById("medicineOutput");
    medicineOutput.innerHTML = "";
    if (searchMedicine != "") {
      $.ajax({
        type: "POST",
        url: "logic/searchMedicine.php",
        data: {
          searchMedicine: searchMedicine,
        },
        dataType: "json",
        success: function (response) {
          for (let i = 0; i < response.length; i++) {
            medicineOutput.innerHTML += `<button class="medicineMove medicineMoveClass txtl" data-idMedicine="${response[i]["id_medicine"]}" data-price='${response[i]["price"]}'>${response[i]["medicine"]}</button>`;
          }
        },
      });
    } else {
      medicineOutput.innerHTML = "";
    }
  });
  $(document).on("input", "#searchMedicine2", function () {
    var searchMedicine = $("#searchMedicine2").val();
    var medicineOutput = document.getElementById("medicineOutputSubAccount");
    medicineOutput.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchMedicine.php",
      data: {
        searchMedicine: searchMedicine,
      },
      dataType: "json",
      success: function (response) {
        if (response == 0) {
          medicineOutput.innerHTML += "<h3>Nema rezultata.</h3>";
        } else {
          for (let i = 0; i < response.length; i++) {
            medicineOutput.innerHTML += `<button class="medicineMove2 medicineMoveClass txtl" data-idMedicine="${response[i]["id_medicine"]}" data-price='${response[i]["price"]}'>${response[i]["medicine"]}</button>`;
          }
        }
      },
    });
  });
  $(document).on("blur", "#searchMedicine2", function () {
    setTimeout(() => {
      document.getElementById("medicineOutputSubAccount").innerHTML = "";
    }, 200);
  });
  // !ZAPAMTI
  $(document).on("click", ".medicineMove", function (e) {
    e.preventDefault();
    var total = document.getElementById("totalPriceMedicines").innerText * 1;
    var idMedicine = $(this).attr("data-idMedicine");
    var price = $(this).attr("data-price") * 1;
    var medicineName = $(this).text();
    var medicineOutput = document.getElementById("medicines1");
    var currentMedicines = Array.from(
      document.querySelectorAll(".mostUsedMedicines")
    );
    var ourArray = currentMedicines.map(
      (x) => x.getAttribute("data-idMedicine") * 1
    );
    if (ourArray.includes(idMedicine * 1)) {
      toast("Već postoji lek", "Greška", "error");
      $("#medicineOutput").html("");
    } else {
      document.getElementById("totalPriceMedicines").innerText = total + price;
      var div = document.createElement("div");
      div.setAttribute("class", "storeInfo");
      var label = document.createElement("label");
      label.classList.add("fl");
      label.setAttribute("for", `${medicineName}`);
      label.innerText = `${medicineName}`;
      var prom = document.createElement("input");
      prom.setAttribute("type", "number");
      prom.setAttribute("class", "mostUsedMedicines medicines");
      prom.setAttribute("min", 1);
      prom.setAttribute("value", 1);
      prom.setAttribute("data-idMedicine", `${idMedicine}`);
      prom.setAttribute("data-priceMedicine", `${price}`);
      prom.setAttribute("name", `${medicineName}`);
      var span = document.createElement("span");
      span.setAttribute("data-price", `${price}`);
      span.setAttribute("class", "priceOfMedicineCustom");
      span.classList.add("fc");
      // <button class='xxx'>X</button>
      var xButton = document.createElement("button");
      xButton.setAttribute("class", "xxx");
      xButton.classList.add("fc");
      xButton.innerHTML = "<svg id='minus' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 50'><path d='M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z'/></svg>";
      span.innerText = `${price}`;
      div.append(label);
      div.append(prom);
      div.append(span);
      div.append(xButton);
      $("#medicines1").append(div);
      $("#medicineOutput").html("");
      ourArray.push(idMedicine * 1);
    }
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
    }, 100);
  });
  $(document).on("click", ".btnPage", function (e) {
    e.preventDefault();
    var page = $(this).text() * 1;
    $.ajax({
      type: "GET",
      url: "logic/viewTables.php",
      data: { page: page },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  $(document).on("input", "#searchAnalysis", function (e) {
    e.preventDefault();
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var input = $("#searchAnalysis").val();
    $.ajax({
      type: "POST",
      url: "logic/searchAnalysis.php",
      data: {
        input: input,
        pageLoc: pageLocation
      },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  $(document).on("input", "#searchMedicines", function (e) {
    e.preventDefault();
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var input = $("#searchMedicines").val();
    $.ajax({
      type: "POST",
      url: "logic/searchMedicinePrice.php",
      data: {
        input: input,
        pageLoc: pageLocation
      },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  $(document).on("input", "#searchMedicineCenovnik", function (e) {
    e.preventDefault();
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var input = $(this).val();
    $.ajax({
      type: "POST",
      url: "logic/searchMedicineCenovnik.php",
      data: {
        input: input,
        pageLoc: pageLocation
      },
      dataType: "json",
      success: function (response) {
        $("#priceCard").html(response);
      },
    });
  });
  
  $(document).on("blur", "#searchMedicineCenovnik", function () {
    $("#searchMedicineCenovnik").val("");
  });
  $(document).on("input", "#searchAppointments", function (e) {
    var input = $("#searchAppointments").val();
    var priceCard = document.getElementById("priceCard");
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    priceCard.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchAppointments.php",
      data: {
        input: input,
        pageLoc: pageLocation
      },
      dataType: "json",
      success: function (response) {
        priceCard.innerHTML = response;
      },
    });
  });
  $(document).on("blur", ".blured", function () {
    $("#searchAnalysis").val("");
    $("#searchAppointments").val("");
  });
  
  $(document).on("click", "#finalButton", function (e) {
    e.preventDefault();
    var idDoctor = $("#chooseDoctor option:selected").val();
    var bloodPrice = document.getElementById("totalPriceBloods").innerText * 1;
    var therapyPrice =
      document.getElementById("totalPriceMedicines").innerText * 1;
    var id = $("#id").val();
    var idEmployee = $("#id_employee").val();
    var idPatient = $("#searchNameSurname").attr("data-id");
    var arr = Array.from(document.querySelectorAll("#finalBloods option"));
    var arr2 = Array.from(
      document.querySelectorAll("#finalAppointments option")
    );
    var selectedBloodsId = arr.map((x) => x.getAttribute("data-id") * 1);
    var selectedAppointmentsIds = arr2.map((x) => +x.getAttribute("data-id"));
    var bloodsTotalPrice = $("#totalPriceBloods").text() * 1;
    var appointmentsTotalPrice = +$("#totalPriceAppointments").text();
    var comment = $("#commentAccount").val();
    var anamneza = $("#anamneza").val();
    var nalaz = $("#nalaz").val();
    var uznalaz = $("#uznalaz").val();
    var terapija = $("#terapija").val();
    var zakljucak = $("#zakljucak").val();
    var idTherapyType = $("#idTherapyType").val() * 1;
    var totalPriceMedicines = $("#totalPriceMedicines").text() * 1;
    var mmedicines = Array.from(
      document.querySelectorAll("#medicineShortcut input.medicines")
    ).filter(x=>x.value>0);
    var mmedicines1 = Array.from(
      document.querySelectorAll("#medicines input.medicines")
    ).filter(x=>x.value>0);
    var array = Array.from(document.querySelectorAll(".medicines"));
    var valuesToCheck2 = {};
    var defaultValues = array.map((x) => x.getAttribute("default") * 1);
    valuesToCheck2["quantity"] = array.map((x) => x.value * 1); //NJEGA PROVERIM SA DEFAULTNIM VREDNOSTIMA
    valuesToCheck2["idMedicine"] = array.map(
      (x) => x.getAttribute("data-idMedicine") * 1
    );
    /* var isZeroAnyone = valuesToCheck2["quantity"].some((x) => x == 0);
    idChosenTherapyId.value = idMemoryValue;
    /* var error = false;
    for (let i = 0; i < defaultValues.length; i++) {
      if (defaultValues[i] != valuesToCheck2["quantity"][i]) {
        error = true;
        idChosenTherapyId.value = "";
        break;
      }
    } */
    valuesToCheck = JSON.stringify(valuesToCheck2);
    mmedicines = mmedicines.concat(mmedicines1);
    var medicines = [];
    for (let i = 0; i < mmedicines.length; i++) {
      medicines.push({
        idMedicine: mmedicines[i].getAttribute("data-idMedicine") * 1,
        quantity: mmedicines[i].value * 1,
        price: mmedicines[i].getAttribute("data-pricemedicine") * 1,
      });
    }
    if (idPatient == undefined || idPatient == null || idPatient == NaN) {
      toast("Morate izabrati Pacijenta.", "Greška", "error");
    } else if (idDoctor == "Izaberi doktora") {
      toast("Morate izabrati Doktora.", "Greška", "error");
    } else {
      $.ajax({
        type: "POST",
        url: "logic/checkValues.php",
        data: {
          valuesToCheck: valuesToCheck,
        },
        success: function (response) {
          if (response == 1) {
            $("#idChosenTherapyId").val(idMemoryValue);
            $("#idTherapyType").val("1");
          } else if (response == 2) {
            $("#idTherapyType").val("2");
            $("#idChosenTherapyId").remove();
          }
        },
      });
      $.ajax({
        type: "POST",
        url: "logic/crudAccount.php",
        data: {
          idDoctor: idDoctor,
          therapyPrice: therapyPrice,
          bloodPrice: bloodPrice,
          id: id,
          idTherapy: idMemoryValue,
          idTherapyType: idTherapyType,
          idEmployee: idEmployee,
          idPatient: idPatient,
          comment: comment,
          anamneza: anamneza,
          nalaz:nalaz,
          uznalaz:uznalaz,
          terapija:terapija,
          zakljucak:zakljucak,
          totalPriceMedicines: totalPriceMedicines,
          bloodsTotalPrice: bloodsTotalPrice,
          appointmentsTotalPrice: appointmentsTotalPrice,
          //ARRAYS
          selectedBloodsId: selectedBloodsId,
          selectedAppointmentsIds: selectedAppointmentsIds,
          medicines: medicines,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            toast("Uspešan unos naloga", "Uspešno", "success");
            setTimeout(() => {
              window.location.replace("nalog.php?sort=4");
            }, 400);
           
          } else if (response == 5) {
            toast("Nema dovoljno lekova u magacinu.", "Greška", "error");
          }
        },
      });
    }
  });
  $(document).on("click", "#x", function (e) {
    e.preventDefault();
    $("#idTherapyType").val("");
    $("#idChosenTherapyId").val("");
    $("#medicines").html("");
    $("#medicines1").html("");
    $("#x").remove();
    $("#totalPriceMedicines").html(0);
    $("#topMedicines").html("");
    calculateTotalPrice(
      document.getElementById("totalPriceBloods"),
      document.getElementById("totalPriceAppointments"),
      document.getElementById("totalPriceMedicines"),
      document.getElementById("totalPriceAccount")
    );
  });
  $(document).on("click", "#x", function (e) {
    e.preventDefault();
    $(".chosenTherapy").removeClass("activecustom");
  });
  
  $(document).on("blur", "#searchMedicine", function () {
    setTimeout(() => {
      $("#medicineOutput").html("");
    }, 200);
  });
  $(document).on("change", ".checkIt", function (e) {
    e.preventDefault();
    var elements = Array.from(document.querySelectorAll(".checkIt"));
    var defaultValues = elements.map((x) => x.getAttribute("default") * 1);
    var inputValues = elements.map((x) => x.value * 1);
    var areTheSame = true;
    for (let i = 0; i < inputValues.length; i++) {
      if (inputValues[i] != defaultValues[i]) {
        areTheSame = false;
        break;
      }
    }
    if (areTheSame) {
      $("#idTherapyType").val(1);
    } else {
      $("#idTherapyType").val(2);
    }
  });
  $(document).on("click", ".deleteButtonAcc", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    $.ajax({
      type: "GET",
      url: "logic/isShownAccount.php",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response == 1) {
          toast("Uspešno izbrisan", "Uspešno", "success");
          //window.location.replace("nalog.php?sort=4");
        } else {
          toast("Došlo je do greške", "Greška", "error");
        }
      },
    });
  });
  $(document).on("click", ".deleteButtonAccEmployee", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    $.ajax({
      type: "GET",
      url: "logic/isShownAccount.php",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response == 1) {
          toast("Uspešno izbrisan", "Uspešno", "success");
          //window.location.replace("adminpanel.php?view=4");
        } else {
          toast("Došlo je do greške", "Greška", "error");
        }
      },
    });
  });
  $(document).on("change", "#sortNalog", () => {
    var sort = $("#sortNalog option:selected").val();
    if (window.location.search == "") {
      window.location.replace(`nalog.php?sort=${sort}`);
    } else {
      if (window.location.search.includes("dateStart")) {
        // dateStart=2021-11-1
        var indexOfDateStart = window.location.search.indexOf("dateStart");
        var indexOfDateEnd = window.location.search.indexOf("dateEnd");
        var dateStart = window.location.search.substring(
          indexOfDateStart + 10,
          indexOfDateStart + 20
        );
        var dateEnd = window.location.search.substring(
          indexOfDateEnd + 8,
          indexOfDateEnd + 18
        );
        window.location.replace(
          `nalog.php?dateStart=${dateStart}&dateEnd=${dateEnd}&sort=${sort}`
        );
      }
      // PAGE=5
      else if (window.location.search.includes("page")) {
        var indexOfPage = window.location.search.indexOf("page");
        var page = window.location.search.substr(indexOfPage, 6);
        window.location.replace(`nalog.php?sort=${sort}&${page}`);
      } else {
        window.location.replace(`nalog.php?sort=${sort}`);
      }
    }
  });
  selectMultipleWithoutHoldingCTRL("#blood");
  selectMultipleWithoutHoldingCTRL("#appointment");
  selectMultipleWithoutHoldingCTRL("#finalBloods");
  selectMultipleWithoutHoldingCTRL("#finalAppointments");
  $(document).on("click", ".exit", exit);
  $(document).on("click", ".exitexit", exitexit);
  $(document).on("click", ".exitexitexit", exitexitexit);
  $(document).on("click", "label[for='option1']", function (e) {
    var accId = +$(this).prev().attr("data-id");
    $.ajax({
      type: "POST",
      url: "logic/check.php",
      data: {
        accId: accId,
      },
      dataType: "json",
      success: function (response) {
        if (response) {
          toast("Čekirali ste nalog", "Uspešno", "success");
          window.location.reload();
        }
      },
    });
  });
  $(document).on("click", ".checkboxCheck1", function () {
    var idSubAccount = $(this).attr("data-id");
    if ($(this).is(":checked")) {
      $.ajax({
        type: "GET",
        url: "logic/check1.php",
        data: {
          idSubAccount: idSubAccount,
        },
        dataType: "json",
        success: function (response) {
          if (response) {
            toast("Čekirali ste podnalog", "Uspešno", "success");
            window.location.reload();
          }
        },
      });
    }
  });
  $(document).on("change", "#dateStart", dates);
  $(document).on("change", "#dateEnd", dates);
  $(document).on("click", "#resetButton", resetButton);
  $(document).on("click", ".updateButtonAcc", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(`nalog.php?sort=4&idAccount=${idAccount}`);
  });
  $(document).on("click", ".updateButtonAccEmployee", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(`adminpanel.php?view=4&idAccount=${idAccount}`);
  });
  $(document).on("click", ".viewButtonAccEmployee", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(`adminpanel.php?view=4&viewAccount=${idAccount}`);
  });
  $(document).on("click", ".viewButtonAccDeleted", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(
      `adminpanel.php?view=5&idAccountDeleted=${idAccount}`
    );
  });
  $(document).on("click", "#exitDelAccountAdminpanel", function (e) {
    e.preventDefault();
    window.location.replace(`adminpanel.php?view=5`);
  });
  selectMultipleWithoutHoldingCTRL("#bloodUpdate");
  selectMultipleWithoutHoldingCTRL("#finalBloodsUpdate");
  selectMultipleWithoutHoldingCTRL("#appointmentUpdate");
  selectMultipleWithoutHoldingCTRL("#finalAppointmentsUpdate");
  $("#moveToFinalBloodsUpdate").on("click", function (e) {
    e.preventDefault();
    var finalBloodsSelect = document.getElementById("finalBloodsUpdate");
    var finalBloodsSelectedOptions = Array.from(
      document.querySelectorAll("#finalBloodsUpdate option")
    );
    var finalBloodsSelectedOptionsIds = finalBloodsSelectedOptions.map(
      (x) => +x.getAttribute("data-id")
    );
    finalBloodsSelect.setAttribute("size", 11);
    var selectedBloods = Array.from($(".chosenBloodUpdate:selected"));
    var totalPriceBloods = document.getElementById("totalPriceBloodsUpdate");
    bloodTotal = Number.parseInt(totalPriceBloods.innerText);
    var selectedBloodsIds = [];
    for (let i = 0; i < selectedBloods.length; i++) {
      selectedBloodsIds.push(
        Number.parseInt($(selectedBloods[i]).attr("data-id"))
      );
      bloodTotal += Number.parseInt($(selectedBloods[i]).attr("data-price"));
    }
    var isAlreadyThere = selectedBloodsIds.some((x) =>
      finalBloodsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedBloods.length; i++) {
      if (!isAlreadyThere) {
        finalBloodsSelect.innerHTML += `<option class='select-list1-item' data-id='${$(
          selectedBloods[i]
        ).attr("data-id")}' data-price='${$(selectedBloods[i]).attr(
          "data-price"
        )}'>${selectedBloods[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Već postoji duplikat krvi.", "Greška", "error")
      : (totalPriceBloods.innerText = bloodTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsUpdate"),
        document.getElementById("totalPriceAppointmentsUpdate"),
        document.getElementById("totalMedicinesPriceUpdate"),
        document.getElementById("totalAccountPriceUpdate")
      );
  });
  $("#moveFromFinalBloodsUpdate").click(function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById("totalPriceBloodsUpdate");
    var finalBloodsSelect = document.getElementById("finalBloodsUpdate");
    var selectedBloodsFinalAll = Array.from($("#finalBloodsUpdate option"));
    var selectedBloodsFinal = Array.from(
      $("#finalBloodsUpdate option:selected")
    );
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsUpdate"),
      document.getElementById("totalPriceAppointmentsUpdate"),
      document.getElementById("totalMedicinesPriceUpdate"),
      document.getElementById("totalAccountPriceUpdate")
    );
  });
  $(document).on("click", "#moveToFinalAppointmentsUpdate", function (e) {
    e.preventDefault();
    var finalAppointmentsSelect = document.getElementById(
      "finalAppointmentsUpdate"
    );
    finalAppointmentsSelect.setAttribute("size", 5);
    var selectedAppointments = Array.from(
      $(".chosenAppointmentUpdate:selected")
    );
    var totalPriceAppointments = document.getElementById(
      "totalPriceAppointmentsUpdate"
    );
    appointmentTotal = Number.parseInt(totalPriceAppointments.innerText);
    var selectedAppointmentsIds = [];
    var finalAppointmetsSelectedOptionsIds = Array.from(
      document.querySelectorAll("#finalAppointmentsUpdate option")
    ).map((x) => +x.getAttribute("data-id"));
    for (let i = 0; i < selectedAppointments.length; i++) {
      selectedAppointmentsIds.push(
        Number.parseInt($(selectedAppointments[i]).attr("data-id"))
      );
      appointmentTotal += Number.parseInt(
        $(selectedAppointments[i]).attr("data-price")
      );
    }
    var isAlreadyThere = selectedAppointmentsIds.some((x) =>
      finalAppointmetsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedAppointments.length; i++) {
      if (!isAlreadyThere) {
        finalAppointmentsSelect.innerHTML += `<option class='select-list1-item' data-id='${$(
          selectedAppointments[i]
        ).attr("data-id")}' data-price='${$(selectedAppointments[i]).attr(
          "data-price"
        )}'>${selectedAppointments[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Već postoji duplikat pregleda.", "Greška", "error")
      : (totalPriceAppointments.innerText = appointmentTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsUpdate"),
        document.getElementById("totalPriceAppointmentsUpdate"),
        document.getElementById("totalMedicinesPriceUpdate"),
        document.getElementById("totalAccountPriceUpdate")
      );
  });
  $(document).on("click", "#moveFromFinalAppointmentsUpdate", function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById(
      "totalPriceAppointmentsUpdate"
    );
    var finalBloodsSelect = document.getElementById("finalAppointmentsUpdate");
    var selectedBloodsFinalAll = Array.from(
      $("#finalAppointmentsUpdate option")
    );
    var selectedBloodsFinal = Array.from(
      $("#finalAppointmentsUpdate option:selected")
    );
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsUpdate"),
      document.getElementById("totalPriceAppointmentsUpdate"),
      document.getElementById("totalMedicinesPriceUpdate"),
      document.getElementById("totalAccountPriceUpdate")
    );
  });
  $(document).on("input", "#searchBloodUpdate", function () {
    var searchBlood = $("#searchBloodUpdate").val();
    var blood = document.getElementById("bloodUpdate");
    blood.innerHTML = "";
    // searchBloodResult.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchBlood.php",
      data: {
        searchBlood: searchBlood,
      },
      dataType: "json",
      success: function (response) {
        // if (response != 0) {
        for (let i = 0; i < response.length; i++) {
          blood.innerHTML += `<option class='chosenBloodUpdate select-list1-item' data-id='${response[i]["id_blood"]}' data-price='${response[i]["price"]}'>${response[i]["analysis"]}</option>`;
        }
        // } else {
        //   searchBloodResult.innerText = "Trazena analiza ne postoji";
        // }
      },
    });
  });
  $(document).on("click", ".deleteMedicine", function (e) {
    e.preventDefault();
    $(this).parent().remove();
    calculateTotalPriceMedicines();
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsUpdate"),
      document.getElementById("totalPriceAppointmentsUpdate"),
      document.getElementById("totalMedicinesPriceUpdate"),
      document.getElementById("totalAccountPriceUpdate")
    );
  });
  $(document).on("input", "#searchMedicineUpdate", function () {
    var searchMedicine = $(this).val();
    var searchedMedicinesDiv = document.getElementById("searchedMedicines");
    searchedMedicinesDiv.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchMedicine.php",
      data: { searchMedicine: searchMedicine },
      dataType: "json",
      success: function (response) {
        for (let i = 0; i < response.length; i++) {
          searchedMedicinesDiv.innerHTML += `<button class='searchedMedicine medicineMoveClass txtl' data-id='${response[i]["id_medicine"]}'>${response[i]["medicine"]}</button>`;
        }
      },
    });
  });
   $(document).on("blur", "#searchMedicineUpdate", function () {
    setTimeout(() => {
      $("#searchedMedicines").html("");
    }, 200);
  }); 
  $(document).on("click", ".searchedMedicine", function (e) {
    e.preventDefault();
    var idMedicine = +$(this).attr("data-id");
    // CHECK IF THAT IDMEDICINE IS ALREADY IN THERAPIES
    var idMedicinesFromTherapies = Array.from(
      document.querySelectorAll(".medicineQuantityUpdate")
    ).map((x) => +x.getAttribute("data-id"));
    if (idMedicinesFromTherapies.includes(idMedicine)) {
      toast("Ovaj lek je već izabran.", "Greška", "error");
    } else {
      $.ajax({
        type: "POST",
        url: "logic/findSingleMedicine.php",
        data: { idMedicine },
        dataType: "json",
        success: function (response) {
           $("#medicinesUpdate").append(`<div class='singleMedicine'><label for="medicineInput" class="fl">${response["medicine"]}</label><input type="number" class='medicineQuantityUpdate' name='medicineInput' data-price='${response["price"]}' data-id='${response["id_medicine"]}' min='1' value='1' ><span class="singleMedicinePriceUpdate fc">${response["price"]}</span><button class='deleteMedicine fc'><svg id='deletebtn' viewBox='0 0 30 30'><path d='M13.81.25c-.13.01-.47.06-.76.1C7.2 1.13 2.31 5.37.75 11.03c-.4 1.49-.52 2.31-.52 3.95-.01 1.64.08 2.32.46 3.79.63 2.45 1.87 4.65 3.63 6.48 2.31 2.41 5.13 3.87 8.52 4.42 1 .17 3.27.16 4.29-.01 2.22-.36 4.26-1.13 6-2.29 3.53-2.35 5.85-5.99 6.5-10.24.16-1.05.16-3.18 0-4.23-.52-3.34-2-6.22-4.41-8.55-2.19-2.11-4.78-3.41-7.9-3.94-.74-.14-2.91-.24-3.51-.16zm6.2 9.34c.35.18.55.51.55.93-.01.18-.04.39-.08.48s-.96 1.02-2.04 2.08l-1.96 1.94 2.03 2c1.78 1.75 2.04 2.04 2.07 2.25.07.38-.03.69-.28.98-.29.32-.63.43-1.02.32-.24-.07-.55-.34-2.29-2.07l-2-2-1.96 2c-1.73 1.76-2 2-2.24 2.07-.75.19-1.45-.47-1.31-1.23.05-.28.21-.46 2.74-3.01l1.29-1.31-1.96-1.96c-2.11-2.13-2.11-2.12-2-2.71.06-.32.46-.72.78-.78.59-.11.58-.11 2.71 2L15 13.53l1.97-1.96c1.08-1.08 2.04-2 2.13-2.03.26-.12.64-.09.91.05z'/></svg></button></div>`) ;
       
          calculateTotalPriceMedicines();
          calculateTotalPrice(
            document.getElementById("totalPriceBloodsUpdate"),
            document.getElementById("totalPriceAppointmentsUpdate"),
            document.getElementById("totalMedicinesPriceUpdate"),
            document.getElementById("totalAccountPriceUpdate")
          );
        },
      });
    }
  });
  $(document).on("change", ".medicineQuantityUpdate", function () {
    var tmQuantity = $(this).val();
    var price = +$(this).attr("data-price");
    $(this)
      .next()
      .text(tmQuantity * price);
    calculateTotalPriceMedicines();
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsUpdate"),
      document.getElementById("totalPriceAppointmentsUpdate"),
      document.getElementById("totalMedicinesPriceUpdate"),
      document.getElementById("totalAccountPriceUpdate")
    );
  });
  $(document).on("click", "#updateButton", function (e) {
    e.preventDefault();
    var idAccount = +$(this).attr("data-idAccount");
    var idDoctor = +$("#chooseDoctorUpdate option:checked").val();
    var bloodsIds = Array.from(
      document.querySelectorAll("#finalBloodsUpdate option")
    ).map((x) => +x.getAttribute("data-id"));
    var appointmentsIds = Array.from(
      document.querySelectorAll("#finalAppointmentsUpdate option")
    ).map((x) => +x.getAttribute("data-id"));
    var medicinesIds = Array.from(
      document.querySelectorAll("#medicinesUpdate .medicineQuantityUpdate")
    ).map((x) => +x.getAttribute("data-id"));
    var medicinesQuantity = Array.from(
      document.querySelectorAll("#medicinesUpdate .medicineQuantityUpdate")
    ).map((x) => +x.value);
    var medicinesPrice = Array.from(
      document.querySelectorAll("#medicinesUpdate .medicineQuantityUpdate")
    ).map((x) => +x.getAttribute("data-price"));
    var totalPriceBloods = +$("#totalPriceBloodsUpdate").text();
    var totalPriceAppointments = +$("#totalPriceAppointmentsUpdate").text();
    var totalPriceMedicines = +$("#totalMedicinesPriceUpdate").text();
    var count = $("#totalAccountPriceUpdate").text().split(" ");
    var totalPrice = count[count.length-1];
    var commentUpdate = $("#commentUpdate").val();
    var anamnezaUpdate = $("#anamnezaUpdate").val();
    var nalazUpdate = $("#nalazUpdate").val();
    var uznalazUpdate = $("#uznalazUpdate").val();
    var terapijaUpdate = $("#terapijaUpdate").val();
    var zakljucakUpdate = $("#zakljucakUpdate").val();
    $.ajax({
      type: "POST",
      url: "logic/updateAccount.php",
      data: {
        commentUpdate: commentUpdate,
        anamnezaUpdate: anamnezaUpdate,
        nalazUpdate: nalazUpdate,
        uznalazUpdate: uznalazUpdate,
        terapijaUpdate: terapijaUpdate,
        zakljucakUpdate: zakljucakUpdate,
        idAccount: idAccount,
        idDoctor: idDoctor,
        bloodsIds: bloodsIds,
        appointmentsIds: appointmentsIds,
        medicinesPrice: medicinesPrice,
        medicinesIds: medicinesIds,
        medicinesQuantity: medicinesQuantity,
        totalPriceBloods: totalPriceBloods,
        totalPriceAppointments: totalPriceAppointments,
        totalPriceMedicines: totalPriceMedicines,
        totalPrice: totalPrice,
      },
      dataType: "json",
      success: function (response) {
        if (response == 1) {
          toast("Uspešno izmenjen nalog", "Uspešno", "success");
          //window.location.replace("nalog.php?sort=4");
        } else if (response == 5) {
          toast("Nedovoljno lekova u magacinu.", "Greška", "error");
        } else if (response == 0)
          toast(
            "Izaberite neke vrednosti kako biste ih promenili.",
            "Greška",
            "error"
          );
      },
    }); 
  });
  $(document).on("click", ".buttonForPrintFull", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.open(`print.php?idAcc=${id}`);
  });
  $(document).on("click", ".buttonForPrintA4", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.open(`printa4.php?idAcc=${id}`);
  });
  $(document).on("click", ".buttonForPrintSliper", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.open(`printsliper.php?idAcc=${id}`, '_blank');
  });
  $(document).on("click", ".buttonForPrintSliper", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.open(`printsliper.php?idAcc=${id}`, '_blank');
  });
  $(document).on("click", ".buttonForPrintDr", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    window.open(`printDr.php?idAcc=${id}`);
  });
  $(document).on("click", ".btnAccountPagination", function (e) {
    e.preventDefault();
    var pageToAdd = +$(this).attr("data-page");
    if (window.location.search.includes("page")) {
      var newSearch = window.location.search.substring(
        0,
        window.location.search.length - 7
      );
      window.location.replace(`nalog.php${newSearch}&page=${pageToAdd}`);
    } else {
      window.location.replace(
        `nalog.php${window.location.search}&page=${pageToAdd}`
      );
    }
  });
  $("#searchPatientExpense").on("input", function () {
    var search = $("#searchPatientExpense").val();
    var searchOutputExpense = document.getElementById("searchOutputExpense");
    searchOutputExpense.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/search.php",
      data: {
        search: search,
      },
      dataType: "json",
      success: function (response) {
        for (let i = 0; i < response.length; i++) {
          searchOutputExpense.innerHTML += `<button class='chosenPatientExpense search-btn search-btn2 btn br33' data-id='${response[i]["id_patient"]}'>${response[i]["name"]} ${response[i]["surname"]}&nbsp;&nbsp;|&nbsp;&nbsp;${response[i]["jmbg"]}</button>`;
        }
      },
    });
  });
  $(document).on("click", ".chosenPatientExpense", function (e) {
    e.preventDefault();
    var idPatient = $(this).attr("data-id");
    var nameSurname = $(this).text();
    nameSurname = nameSurname.substring(0, nameSurname.length - 18);
    var searchNameSurnameExpense = document.getElementById(
      "searchPatientExpense"
    );
    searchOutputExpense.innerHTML = "";
    searchNameSurnameExpense.setAttribute("data-id", idPatient);
    searchNameSurnameExpense.value = nameSurname;
  });
  $(document).on("click", "#insertExpense", function (e) {
    e.preventDefault();
    var patientId = +$("#searchPatientExpense").attr("data-id");
    var commentExpense = $("#expensesComment").val();
    if ([NaN, undefined, null].includes(patientId)) {
      toast("Morate izabrati pacijenta.", "Greška", "error");
    } else if ([NaN, undefined, null, ""].includes(commentExpense)) {
      toast("Naziv usluge ne sme ostati prazan.", "Greška", "error");
    } else {
      var expensePrice = +$("#expensePrice").val();
      if (expensePrice > 0) {
        $.ajax({
          type: "POST",
          url: "logic/insertExpense.php",
          data: {
            patientId: patientId,
            commentExpense: commentExpense,
            expensePrice: expensePrice,
          },
          dataType: "json",
          success: function (response) {
            if (response == 1) {
              toast("Uspešno ste dodali trošak.", "Uspešno", "success");
              window.location.reload();
            } else if (response == 0) {
              toast("Došlo je do greške.", "Greška", "error");
            }
          },
        });
      } else {
        toast("Cena mora biti veća od 0.", "Greška", "error");
      }
    }
  });
  $(document).on("click", "#addMedicineButton", function (e) {
    e.preventDefault();
    window.location.replace("adminpanel.php?view=2");
  });
  $(document).on("click", ".quantityToAdd", function (e) {
    e.preventDefault();
    var idMedicine = +$(this).attr("data-id");
    var quantityToAdd = +$(this).parent().find("input").val();
    if (quantityToAdd > 0) {
      $.ajax({
        type: "POST",
        url: "logic/updateMedicine.php",
        data: {
          idMedicine: idMedicine,
          quantityToAdd: quantityToAdd,
        },
        dataType: "json",
        success: function (response) {
          if (response == 0) {
            toast("Greška sa bazom.", "Greška", "error");
          } else {
            toast("Uspešno dodavanje leka.", "Uspešno", "success");
            window.location.reload();
          }
        },
      });
    } else {
      toast("Količina mora biti veća od 0.", "Greška", "error");
    }
  });
  $(document).on("click", ".quantityToSubtract", function (e) {
    e.preventDefault();
    var idMedicine = +$(this).attr("data-id");
    var quantityToAdd = +$(this).parent().find("input").val();
    if (quantityToAdd > 0) {
      $.ajax({
        type: "POST",
        url: "logic/updateMedicine1.php",
        data: {
          idMedicine: idMedicine,
          quantityToAdd: quantityToAdd,
        },
        dataType: "json",
        success: function (response) {
          if (response == 0) {
            toast("Greška sa bazom.", "Greška", "error");
          } else {
            toast("Uspešno oduzimanje leka.", "Uspešno", "success");
            window.location.reload();
          }
        },
      });
    } else {
      toast("Količina mora biti veća od 0.", "Greška", "error");
    }
  });
  $(document).on("click", ".deleteExpense", function (e) {
    e.preventDefault();
    var idExpense = +$(this).attr("data-id");
    $.ajax({
      type: "POST",
      url: "logic/hideExpense.php",
      data: { idExpense: idExpense },
      dataType: "json",
      success: function (response) {
        if (response == 0) {
          toast("Greška sa bazom.", "Greška", "error");
        } else if (response == 1) {
          toast("Uspešno obrisan trošak.", "Uspešno", "success");
          window.location.reload();
        }
      },
    });
  });
  $(document).on("click", "#exitAccount1", function (e) {
    var equalSign = window.location.search.indexOf("=");
    var idSubAccount = +window.location.search.slice(
      equalSign + 1,
      window.location.search.length
    );
    e.preventDefault();
    $.ajax({
      type: "GET",
      url: "logic/deleteSubAccount.php",
      data: { idSubAccount: idSubAccount },
      dataType: "json",
      success: function (response) {
        if (response) {
          window.location.replace("nalog.php?sort=4");
        } else {
          toast("Greška sa bazom.", "Greška", "error");
        }
      },
    });
  });
  $(document).on("click", ".closeMe", function () {
    window.location.replace("adminpanel.php?view=4");
  });
  $(document).on("click", "#exitAccountAp", function (e) {
    var idSubAccount = window.location.search.split("&")[1].split("=")[1];
    e.preventDefault();
    $.ajax({
      type: "GET",
      url: "logic/deleteSubAccount.php",
      data: { idSubAccount: idSubAccount },
      dataType: "json",
      success: function (response) {
        if (response) {
          window.location.replace("adminpanel.php?view=4");
        } else {
          toast("Greška sa bazom.", "Greška", "error");
        }
      },
    });
  });
  $(document).on("click", "#deleteAllExpenses", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "logic/deleteAllExpenses.php",
      data: {},
      dataType: "json",
      success: function (response) {
        if (response == 0) {
          toast("Greška sa bazom.", "Greška", "error");
        } else if (response == 1) {
          toast("Uspešno brisanje.", "Uspešno", "success");
          window.location.reload();
        }
      },
    });
  });
  $(document).on("click", "#showEmployees", function (e) {
    e.preventDefault();
    window.location.replace("adminpanel.php?view=4");
  });
  $(document).on("click", "#bills", function (e) {
    e.preventDefault();
    window.location.replace("adminpanel.php?view=3");
  });
  $(document).on("click", "#bank", function (e) {
    e.preventDefault();
    window.location.replace("adminpanel.php?view=1");
  });
  $(document).on("click", "#exitAccount", function (e) {
    e.preventDefault();
    window.location.replace("nalog.php?sort=4");
  });
  //!BITNO OVDE JAKO
  //!BITNO OVDE JAKO
  //!BITNO OVDE JAKO
  //!BITNO OVDE JAKO
  $(document).on("click", "#exitAccountAdminpanel", function (e) {
    e.preventDefault();
    window.location.replace("adminpanel.php?view=4");
  });
  $(document).on("click", ".duplicateButtonAcc", function (e) {
    e.preventDefault();
    var idAccount = +$(this).attr("data-id");
    $.ajax({
      type: "GET",
      url: "logic/duplicateMaster.php",
      data: {
        idAccount: idAccount,
      },
      dataType: "json",
      success: function (response) {
        if (response != 0) {
          window.location.replace(`nalog.php?subAccount=${response}`);
        }
      },
    });
  });
  $(document).on("click", ".duplicateButtonAccEmployee", function (e) {
    e.preventDefault();
    var idAccount = +$(this).attr("data-id");
    $.ajax({
      type: "GET",
      url: "logic/duplicateMaster.php",
      data: {
        idAccount: idAccount,
      },
      dataType: "json",
      success: function (response) {
        if (response != 0) {
          window.location.replace(
            `adminpanel.php?view=4&subAccount=${response}`
          );
        }
      },
    });
  });
  $(document).on("click", ".viewButtonAcc", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(`nalog.php?viewAccount=${idAccount}`);
  });
  $(document).on("click", ".printButtonAcc", function (e) {
    e.preventDefault();
    var idAccount = $(this).attr("data-id");
    window.location.replace(`nalog.php?printAccount=${idAccount}`);
  });
$(document).on("click",   "#exitPrint",function (e) {
  e.preventDefault();
  window.location.replace(`nalog.php?sort=4`)
})
$(document).on("click",   "#exitPrintAp",function (e) {
  e.preventDefault();
  window.location.replace(`adminpanel.php?view=4`)
});
  $(document).on("click", ".printButtonSubAcc", function (e) {
   e.preventDefault();
    var pageLocation = window.location.pathname;  
    pageLocation = pageLocation.split("/");
    pageLocation = pageLocation[pageLocation.length-1];
    var idAccount = $(this).attr("data-id");
    
    if(pageLocation == "adminpanel.php"){
      
      window.location.replace(`adminpanel.php?view=4&printSubAccount=${idAccount}`);
    }
    else if(pageLocation == 'nalog.php'){
      window.location.replace(`nalog.php?sort=4&printSubAccount=${idAccount}`)
    }
  });
  $(document).on("click", ".addBloodSubAccount", function (e) {
    var showAppointments = document.getElementById("showAppointments");
    var showTherapy = document.getElementById("showTherapy");
    var showBloods = document.getElementById("showBloods");
    showBloods.style = "display: block";
    showTherapy.style = "display: none";
    showAppointments.style = "display: none";
    if (document.getElementById("mostFinalBloods") != null) {
    } else {
      $.ajax({
        type: "GET",
        url: "logic/getBloods.php",
        data: {},
        dataType: "json",
        success: function (response) {
          var output = "";
          output +=
            "<div class='search-component fc mt10'><span class='fc'><svg id='search' xmlns='http://www.w3.org/2000/svg' width='682.667' height='682.667' viewBox='0 0 512 512' preserveAspectRatio='xMidYMid meet'><path d='M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z'></path></svg></span><input class='search-input fc' type='text' id='analysisSearch' placeholder='Unesite naziv analize...'><div id='analysisOutput'></div></div>";
          output +=
            "<div class='fc mauto'><select class='select-list1 scroll2 pr10' id='finalBloodsSubAccounts' multiple>";
          for (let i = 0; i < response.length; i++) {
            output += `<option class='chosenBloodSubAccount select-list1-item' data-id='${response[i]["id_blood"]}' data-price='${response[i]["price"]}'>${response[i]["analysis"]}</option>"`;
          }
          output +=
            "</select><select class='select-list1 pr10 scroll2' id='mostFinalBloods' multiple></select></div><div class='fc'><button class='select-list1-btn s-l1-btn1 btn brbl16' id='moveBloods'>+</button><button class='select-list1-btn s-l1-btn2 btn brbr16' id='retrieveBloods'>-</button></div></div>";
          showBloods.innerHTML = output;
          selectMultipleWithoutHoldingCTRL("#finalBloodsSubAccounts");
          selectMultipleWithoutHoldingCTRL("#mostFinalBloods");
        },
      });
    }
  });
  $(document).on("click", "#moveBloods", function (e) {
    e.preventDefault();
    var finalBloodsSelect = document.getElementById("mostFinalBloods");
    var finalBloodsSelectedOptions = Array.from(
      document.querySelectorAll("#mostFinalBloods option")
    );
    var finalBloodsSelectedOptionsIds = finalBloodsSelectedOptions.map(
      (x) => +x.getAttribute("data-id")
    );
    finalBloodsSelect.setAttribute("size", 11);
    var selectedBloods = Array.from($(".chosenBloodSubAccount:selected"));
    var totalPriceBloods = document.getElementById(
      "totalPriceBloodsSubAccount"
    );
    bloodTotal = Number.parseInt(totalPriceBloods.innerText);
    var selectedBloodsIds = [];
    for (let i = 0; i < selectedBloods.length; i++) {
      selectedBloodsIds.push(
        Number.parseInt($(selectedBloods[i]).attr("data-id"))
      );
      bloodTotal += Number.parseInt($(selectedBloods[i]).attr("data-price"));
    }
    var isAlreadyThere = selectedBloodsIds.some((x) =>
      finalBloodsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedBloods.length; i++) {
      if (!isAlreadyThere) {
        finalBloodsSelect.innerHTML += `<option class="removeBloodSubAccount select-list1-item" data-id='${$(
          selectedBloods[i]
        ).attr("data-id")}' data-price='${$(selectedBloods[i]).attr(
          "data-price"
        )}'>${selectedBloods[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Već postoji duplikat krvi.", "Greška", "error")
      : (totalPriceBloods.innerText = bloodTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsSubAccount"),
        document.getElementById("totalPriceAppointmentsSubAccount"),
        document.getElementById("totalPriceTherapySubAccount"),
        document.getElementById("totalPriceSubAccount")
      );
  });
  $(document).on("click", "#retrieveBloods", function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById(
      "totalPriceBloodsSubAccount"
    );
    var finalBloodsSelect = document.getElementById("mostFinalBloods");
    var selectedBloodsFinalAll = Array.from($("#mostFinalBloods option"));
    var selectedBloodsFinal = Array.from($("#mostFinalBloods option:selected"));
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  });
  $(document).on("input", "#analysisSearch", function (e) {
    var searchBlood = $("#analysisSearch").val();
    var blood = document.getElementById("finalBloodsSubAccounts");
    blood.innerHTML = "";
    $.ajax({
      type: "POST",
      url: "logic/searchBlood.php",
      data: {
        searchBlood: searchBlood,
      },
      dataType: "json",
      success: function (response) {
        if (response) {
          for (let i = 0; i < response.length; i++) {
            blood.innerHTML += `<option class='chosenBloodSubAccount select-list1-item' data-id='${response[i]["id_blood"]}' data-price='${response[i]["price"]}'>${response[i]["analysis"]}</option>`;
          }
        }
      },
    });
  });
  $(document).on("input", "#appointmentsSearch", function (e) {
    var input = $("#appointmentsSearch").val();
    var appointment = document.getElementById("finalAppointmentsSubAccounts");
    $.ajax({
      type: "POST",
      url: "logic/searchAppointments1.php",
      data: {
        input: input,
      },
      dataType: "json",
      success: function (response) {
        appointment.innerHTML = "";
        if (response) {
          for (let i = 0; i < response.length; i++) {
            appointment.innerHTML += `<option class='chosenAppointmentSubAccount select-list1-item' data-id='${response[i]["id_appointment"]}' data-price='${response[i]["price"]}'>${response[i]["appointment"]}</option>`;
          }
        }
      },
    });
  });
  $(document).on("click", ".addAppointmentSubAccount", function (e) {
    e.preventDefault();
    var showAppointments = document.getElementById("showAppointments");
    var showTherapy = document.getElementById("showTherapy");
    var showBloods = document.getElementById("showBloods");
    showBloods.style = "display: none";
    showTherapy.style = "display: none";
    showAppointments.style = "display: block";
    if (document.getElementById("mostFinalAppointments") != null) {
    } else {
      $.ajax({
        type: "GET",
        url: "logic/getAppointments.php",
        data: {},
        dataType: "json",
        success: function (response) {
          var output = "";
          output +=
            "<div class='search-component fc mt10'><span class='fc'><svg id='search' xmlns='http://www.w3.org/2000/svg' width='682.667' height='682.667' viewBox='0 0 512 512' preserveAspectRatio='xMidYMid meet'><path d='M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z'></path></svg></span><input class='search-input fc' type='text' id='appointmentsSearch' placeholder='Unesite naziv pregleda...'><div id='appointmentsOutput'></div></div>";
          output +=
            "<div class='fc'><select class='select-list1 pr10 scroll2 brtl13' id='finalAppointmentsSubAccounts' multiple>";
          for (let i = 0; i < response.length; i++) {
            output += `<option class='chosenAppointmentSubAccount select-list1-item' data-id='${response[i]["id_appointment"]}' data-price='${response[i]["price"]}'>${response[i]["appointment"]}</option>"`;
          }
          output +=
            "</select><select class='select-list1 pr10 scroll2 brtr13' id='mostFinalAppointments' multiple></select></div><div class='fc'><button class='select-list1-btn s-l1-btn1 btn brbl16' id='moveAppointments'>+</button><button class='select-list1-btn s-l1-btn2 btn brbr16' id='retrieveAppointments'>-</button></div>";
          
          showAppointments.innerHTML = output;
          selectMultipleWithoutHoldingCTRL("#finalAppointmentsSubAccounts");
          selectMultipleWithoutHoldingCTRL("#mostFinalAppointments");
        },
      });
    }
  });
  $(document).on("click", "#moveAppointments", function (e) {
    e.preventDefault();
    var finalAppointmentsSelect = document.getElementById(
      "mostFinalAppointments"
    );
    var selectedAppointments = Array.from(
      $(".chosenAppointmentSubAccount:selected")
    );
    var totalPriceAppointments = document.getElementById(
      "totalPriceAppointmentsSubAccount"
    );
    appointmentTotal = Number.parseInt(totalPriceAppointments.innerText);
    var selectedAppointmentsIds = [];
    var finalAppointmetsSelectedOptionsIds = Array.from(
      document.querySelectorAll("#mostFinalAppointments option")
    ).map((x) => +x.getAttribute("data-id"));
    for (let i = 0; i < selectedAppointments.length; i++) {
      selectedAppointmentsIds.push(
        Number.parseInt($(selectedAppointments[i]).attr("data-id"))
      );
      appointmentTotal += Number.parseInt(
        $(selectedAppointments[i]).attr("data-price")
      );
    }
    var isAlreadyThere = selectedAppointmentsIds.some((x) =>
      finalAppointmetsSelectedOptionsIds.includes(x)
    );
    for (let i = 0; i < selectedAppointments.length; i++) {
      if (!isAlreadyThere) {
        finalAppointmentsSelect.innerHTML += `<option class='select-list1-item' data-id='${$(
          selectedAppointments[i]
        ).attr("data-id")}' data-price='${$(selectedAppointments[i]).attr(
          "data-price"
        )}'>${selectedAppointments[i].innerText}</option>`;
      }
    }
    isAlreadyThere
      ? toast("Već postoji duplikat pregleda.", "Greška", "error")
      : (totalPriceAppointments.innerText = appointmentTotal),
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsSubAccount"),
        document.getElementById("totalPriceAppointmentsSubAccount"),
        document.getElementById("totalPriceTherapySubAccount"),
        document.getElementById("totalPriceSubAccount")
      );
  });
  $(document).on("click", "#retrieveAppointments", function (e) {
    e.preventDefault();
    var totalPriceBloods = document.getElementById(
      "totalPriceAppointmentsSubAccount"
    );
    var finalBloodsSelect = document.getElementById("mostFinalAppointments");
    var selectedBloodsFinalAll = Array.from($("#mostFinalAppointments option"));
    var selectedBloodsFinal = Array.from(
      $("#mostFinalAppointments option:selected")
    );
    totalPriceBloods.innerText = "";
    finalBloodsSelect.innerHTML = "";
    removeSelectedBloods(
      selectedBloodsFinalAll,
      selectedBloodsFinal,
      finalBloodsSelect,
      totalPriceBloods
    );
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  });
  $(document).on("click", ".addTherapySubAccount", function (e) {
    e.preventDefault();
    var output = "";
    var showTherapy = document.getElementById("showTherapy");
    var showBloods = document.getElementById("showBloods");
    var showAppointments = document.getElementById("showAppointments");
    showBloods.style = "display: none";
    showTherapy.style = "display: block";
    showAppointments.style = "display: none";
    $("#totalPriceTherapySubAccount").text("0")
      $.ajax({
        type: "GET",
        url: "logic/getTherapies.php",
        data: {},
        dataType: "json",
        success: function (response) {
          output += `
          
          <div class="make-therapy">
            <div class="therapyCustomSize">
              <div class="therapiesResponsive fc">
                <p class="label-h1 fc mr20 fs16">Profili kostura</p>
                  <div class="fc therapiesButtons" id="therapies">`;
                  for (let i = 0; i < response.length; i++) {
                  output += `<button class='chosenTherapySubAccount' data-id='${response[i]["id_therapy"]}'>${response[i]["therapy"]}</button>`;
                  }
                  output += `<button class="fc" id="customTherapySubAccount">Napravi terapiju</button>
                  </div><span id="addRemoveButton1"></span>
        </div>
        
        
        
        <div id="medicines"></div>
        
        <div id="medicineOutput"></div>
        <div id="topMedicines"></div>
        <div class='fc' id="medicines1"></div>
        <span id="addRemoveButton"></span>
        <div id="completedTherapy"></div>

            <div class='apoteka container85'>
            <div class='search-apoteka dupapoteka'>
            <label class='label-h1 fs16 fc mt0' for="searchMedicine">Pretrazi lekove u apoteci</label>

        <div class='search-component sc3 fc'><span class="fc"><svg id="search" xmlns="http://www.w3.org/2000/svg" width="682.667" height="682.667" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
      <path d="M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z"></path>
  </svg></span><input class='search-input fc' placeholder="Pretraži apoteku...." type="text" name="searchMedicine2" id="searchMedicine2"></div>
  <div class='medicineOutputClassFront scroll2' id='medicineOutputSubAccount'></div></div>
        </div>
  
  


        <div class='fdcolumn'>
          <div class='fc' id='startingTherapy1'></div>
          
          <div id='topMed'></div>
          
          
          
        </div>
        <div class='scroll2 fdcolumn' id='medicinesSubAccount'></div>
        <div class='fnoalign apotekaflex2'>
        <div class='materijal-med noneshow'><p class='fs20 gray wght600 txtc'>Potrošni materijal</p><div class='potrosni-materijal scroll2'></div></div><div class='materijal-med noneshow'><p class='fs20 gray wght600 txtc'>Favoriti</p><div class='fav-medicines scroll2'></div></div></div>
          
        </div>
        

        
  
          <label class="fc wght600 gray" for="commentAccount">Komentar</label>
          <textarea name="commentAccount" id="commentAccount1" class="commentTherpay fc scroll2 mb30" cols="30" rows="10" placeholder="Unesite vaš komentar u podnalog pacijenta... (Zaposleni)"></textarea>

  </div>
        
      
        
    </div>
</div>`;
          showTherapy.innerHTML = output;
         
        },
      });
    
  });

  $(document).on("click", "#customTherapySubAccount", addRemoveButton1);
  $(document).on("click", ".chosenTherapySubAccount", addRemoveButton1);
  $(document).on("click", "#x1", function (e) {
    // !medicinesSubAccount
    e.preventDefault();
    
    $(".search-apoteka").css("display","none");
    $("#idTherapyType").val("");
    $("#idChosenTherapyId").val("");
    $("#medicinesSubAccount").html("");
    $(".noneshow").css("display","none");
    $("#startingTherapy1").html("");
    $("#topMed").html("");
    $("#x1").remove();
    $("#totalPriceMedicines").html(0);
    $("#totalPriceTherapySubAccount").html(0);
    $("#totalPriceSubAccount").html(0);
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  });
  var counterClicker = 0;
  $(document).on("click", "#customTherapySubAccount", function (e) {
    e.preventDefault();
    $("#totalPriceTherapySubAccount").text(0);
    $("#totalPriceSubAccount").text(0);
    $("#startingTherapy1").css("display","block");
    $(".search-apoteka").css("display","block");
    $(".noneshow").css("display","block");
    $("#idChosenTherapyId").remove();
    
    idTherapyType.setAttribute("value", "2");
    if (counterClicker == 0) {
      document.getElementById(
        "medicinesSubAccount"
      ).parentElement.innerHTML += `<form class='' id='medicineForm'></form>`;
      /* ).parentElement.innerHTML += `<div class='fdcolumn3'><div class='mb10 fdcolumn' id='topMed'></div><form class='' id='medicineForm'><div class='fc mb20 mt20' id='startingTherapy1'></div></form></div>`; */
    }
    counterClicker++;
    var topMedicines = document.getElementById("medicinesSubAccount");
    var totalPriceMedicines = document.getElementById("totalPriceMedicines");
    var divForDelete = document.getElementById("medicines1");
    divForDelete != null || divForDelete != undefined
      ? (divForDelete.innerHTML = "")
      : "";
    totalPriceMedicines.innerText = 0;
    document.getElementsByClassName("fav-medicines")[0].innerHTML="";
    document.getElementsByClassName("potrosni-materijal")[0].innerHTML="";
    topMedicines.innerHTML = "";
    const r = async () => {
      var print = "";
      $.ajax({
        type: "GET",
        url: "logic/getTherapies.php",
        data: {},
        dataType: "json",
        success: await function (response) {
          print += ``;
          response.forEach((r) => {
            print += `<button class='btn startingTherapy1' data-idTherapy='${r["id_therapy"]}'>${r["therapy"]}</button>`;
          });
          $("#startingTherapy1").html(print);
        },
      });
    };
    r().then(() => {
      $.ajax({
        type: "POST",
        url: "logic/getTopMedicine.php",
        data: {},
        dataType: "json",
        success: function (response) {
          
          topMedicines.innerHTML += "<form id='medicineForm'>";
          for (let i = 0; i < response.length; i++) {
            topMedicines.innerHTML += `<div class='single fl'><label class='fl scroll2' for="${response[i]["medicine"]}">${response[i]["medicine"]}</label><input type="number" data-priceMedicine='${response[i]["price"]}' class='mostUsedMedicines1 medicines' data-idMedicine='${response[i]["id_medicine"]}' name='${response[i]["medicine"]}' min='1'><span data-price='${response[i]["price"]}' class="priceOfMedicineCustom1 fc">0</span><button class='xxx1 fc'><svg id="minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z"/></svg></button>`;
          }
          topMedicines.innerHTML += "</div></form>";
          $( "#medicinesSubAccount .single:nth-child(-n+17)" ).prependTo('.potrosni-materijal');
          $("#medicinesSubAccount .single").slice(0,999).prependTo('.fav-medicines');
        },
        
      });
    });
  });
  
  $(document).on("click", ".chosenTherapySubAccount", function (e) {
    
    e.preventDefault();
    $("#topMed").html("");
    $(".materijal-med").css("display","none");
    $("#startingTherapy1").css("display","none");
    // $(".search-apoteka").html("");
    $(".search-apoteka").css("display", "none");
    document.querySelector("body").append(idChosenTherapyId);
    idTherapyType.setAttribute("value", "1");
    var totalPriceMedicinesSpan = document.getElementById(
      "totalPriceTherapySubAccount"
    );
    var totalPriceMedicines = 0;
    var idTherapy = $(this).attr("data-id");
    idMemoryValue = idTherapy;
    idChosenTherapyId.value = idMemoryValue;
    var medicinesDiv = document.getElementById("medicinesSubAccount");
    if (idTherapy != previousIdTherapy) {
      $.ajax({
        type: "POST",
        url: "logic/getMedicines.php",
        data: {
          idTherapy: idTherapy,
        },
        dataType: "json",
        success: function (r) {
          previousIdTherapy = r[0]["id_therapy"];
          var output = ``;
          for (let i = 0; i < r.length; i++) {
            output += `<div class='single fl'><label class='fl scroll2'>${
              r[i]["medicine"]
            }</label><input type='number' min='0' default='${
              r[i]["tmQuantity"]
            }' class='medicines1 checkIt' data-idMedicine='${
              r[i]["id_medicine"]
            }' value='${r[i]["tmQuantity"]}' data-priceMedicine='${
              r[i]["price"]
            }'/><span class='medicinePrice fc' id='${r[i]["id_medicine"]}'>${
              r[i]["price"] * r[i]["tmQuantity"]
            }</span></div>`;
          }
          medicinesDiv.innerHTML = output;
          var arrayOfMedicinePrices = Array.from(
            document.querySelectorAll(".medicinePrice")
          );
          var finalArrayOfMedicinePrices = arrayOfMedicinePrices.map((x) =>
            Number.parseInt(x.innerText)
          );
          totalPriceMedicines = calculateSum(finalArrayOfMedicinePrices);
          totalPriceMedicinesSpan.innerText = totalPriceMedicines;
          calculateTotalPrice(
            document.getElementById("totalPriceBloodsSubAccount"),
            document.getElementById("totalPriceAppointmentsSubAccount"),
            document.getElementById("totalPriceTherapySubAccount"),
            document.getElementById("totalPriceSubAccount")
          );
        },
      });
    } else {
      medicinesDiv.innerHTML = "";
      $.ajax({
        type: "POST",
        url: "logic/getMedicines.php",
        data: {
          idTherapy: idTherapy,
        },
        dataType: "json",
        success: function (r) {
          var output = ``;
          for (let i = 0; i < r.length; i++) {
            output += `<div class='single fl'><label class='fl scroll2'>${
              r[i]["medicine"]
            }</label><input type='number' default='${
              r[i]["tmQuantity"]
            }' min='0' class='medicines1 checkIt' data-id='${
              r[i]["id_medicine"]
            }' value='${r[i]["tmQuantity"]}' data-priceMedicine='${
              r[i]["price"]
            }'/><span class='medicinePrice fc' id='${r[i]["id_medicine"]}'>${
              r[i]["price"] * r[i]["tmQuantity"]
            }</span></div>`;
          }
          medicinesDiv.innerHTML = output;
          var arrayOfMedicinePrices = Array.from(
            document.querySelectorAll(".medicinePrice")
          );
          var finalArrayOfMedicinePrices = arrayOfMedicinePrices.map((x) =>
            Number.parseInt(x.innerText)
          );
          totalPriceMedicines = calculateSum(finalArrayOfMedicinePrices);
          totalPriceMedicinesSpan.innerText = totalPriceMedicines;
        },
      });
    }
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloods"),
        document.getElementById("totalPriceAppointments"),
        document.getElementById("totalPriceMedicines"),
        document.getElementById("totalPriceAccount")
      );
    }, 100);
  });
  $(document).on("change", ".medicines1", function (e) {
    e.preventDefault();
    var medicineId = $(this).attr("data-id");
    var medicineQuantity = $(this).val();
    var medicinePrice = Number.parseInt($(this).attr("data-priceMedicine"));
    var medicinePriceSpan = $(this).next();
    medicinePriceSpan.html(medicinePrice * medicineQuantity);
    var total = 0;
    var prices = Array.from(document.querySelectorAll(".medicinePrice"));
    var newArray = prices.map((x) => Number.parseInt(x.innerText));
    var total = calculateSum(newArray);
    $("#totalPriceTherapySubAccount").text(total);
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  });
  /* $(document).on("click", ".xxx1", function (e) {
    e.preventDefault();
    var price = +$(this).prev().text();
    var totalPriceMedicines = document.getElementById(
      "totalPriceTherapySubAccount"
    );
    // $(this).next().remove();
    // $(this).prev().remove();
    // $(this).prev().remove();
    // $(this).prev().remove();
    // $(this).remove();
    totalPriceMedicines.innerText = +totalPriceMedicines.innerText - price;
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsSubAccount"),
        document.getElementById("totalPriceAppointmentsSubAccount"),
        document.getElementById("totalPriceTherapySubAccount"),
        document.getElementById("totalPriceSubAccount")
      );
    }, 100);
  }); */
  $(document).on("click", ".xxx1", function (e) {
    e.preventDefault();
    var price = +$(this).prev().text();
    var totalPriceMedicines = document.getElementById("totalPriceTherapySubAccount");
    /* $(this).next().remove();
    $(this).prev().remove();
    $(this).prev().remove();
    $(this).prev().remove();
    $(this).remove(); */
    var input = $(this).prev().prev();
    input.val("");
    $(this).prev().text(0);
    /* input.value = ""; */
    totalPriceMedicines.innerText = +totalPriceMedicines.innerText - price;
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsSubAccount"),
        document.getElementById("totalPriceAppointmentsSubAccount"),
        document.getElementById("totalPriceTherapySubAccount"),
        document.getElementById("totalPriceSubAccount")
      );
    }, 100);
  });
  $(document).on("click", ".medicineMove2", function (e) {
    e.preventDefault();
    var total = document.getElementById("totalPriceSubAccount").innerText * 1;
    var idMedicine = $(this).attr("data-idMedicine");
    var price = $(this).attr("data-price") * 1;
    var medicineName = $(this).text();
    var medicineOutput = document.getElementById("medicinesSubAccount");
    var currentMedicines = Array.from(
      document.querySelectorAll(".mostUsedMedicines1")
    );
    var ourArray = currentMedicines.map(
      (x) => x.getAttribute("data-idMedicine") * 1
    );
    if (ourArray.includes(idMedicine * 1)) {
      toast("Već postoji lek.", "Greška", "error");
      $("#medicineOutputSubAccount").html("");
    } else {
      document.getElementById("totalPriceTherapySubAccount").innerText =
        total + price;
      var div = document.createElement("div");
      div.setAttribute("class", "storeInfo");
      var label = document.createElement("label");
      label.setAttribute("for", `${medicineName}`);
      label.innerText = `${medicineName}`;
      var prom = document.createElement("input");
      prom.setAttribute("type", "number");
      prom.setAttribute("class", "mostUsedMedicines1 medicines");
      prom.setAttribute("min", 1);
      prom.setAttribute("value", 1);
      prom.setAttribute("data-idMedicine", `${idMedicine}`);
      prom.setAttribute("data-priceMedicine", `${price}`);
      prom.setAttribute("name", `${medicineName}`);
      var span = document.createElement("span");
      span.setAttribute("data-price", `${price}`);
      span.setAttribute("class", "priceOfMedicineCustom1");
      // <button class='xxx'>X</button>
      var xButton = document.createElement("button");
      xButton.setAttribute("class", "xxx1");
      xButton.innerHTML = "<svg class='fc' id='minus' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 50'><path d='M21.74.5C13.4 1.66 6.25 6.86 2.67 14.4c-.98 2.07-1.63 4.12-2.1 6.66-.29 1.58-.29 6.3 0 7.88 1.01 5.46 3.23 9.78 6.96 13.51 3.72 3.72 8.1 5.98 13.45 6.95 1.4.25 5.12.35 6.67.17 6.85-.79 13.04-4.33 17.1-9.79 2.4-3.21 3.86-6.64 4.61-10.78.25-1.42.36-4.9.19-6.56-1.2-11.53-10.32-20.71-21.85-22-1.41-.15-4.62-.12-5.96.06zM38.4 22.79c1.89.97 1.86 3.54-.03 4.42-.44.21-1.05.22-13.41.22-12.52 0-12.96-.01-13.38-.22-1.89-.97-1.86-3.45.03-4.45.31-.17 1.74-.19 13.36-.2 12.57.01 13.01.02 13.43.23z'/></svg>";
      span.innerText = `${price}`;
      div.append(label);
      div.append(prom);
      div.append(span);
      div.append(xButton);
      $("#topMed").append(div);
      $("#medicineOutputSubAccount").html("");
      ourArray.push(idMedicine * 1);
    }
    setTimeout(() => {
      calculateTotalPrice(
        document.getElementById("totalPriceBloodsSubAccount"),
        document.getElementById("totalPriceAppointmentsSubAccount"),
        document.getElementById("totalPriceTherapySubAccount"),
        document.getElementById("totalPriceSubAccount")
      );
    }, 100);
  });
  $(document).on("change", ".mostUsedMedicines1", function () {
    var quantity = $(this).val();
    var medicinePriceSpan = $(this).next();
    var price = $(this).next().attr("data-price") * 1;
    medicinePriceSpan.html(quantity * price);
    var totalPrice = Array.from(
      document.querySelectorAll(".priceOfMedicineCustom1")
    );
    var mappedArray = totalPrice.map((x) => x.innerText * 1);
    var finalPrice = calculateSum(mappedArray);
    document.getElementById("totalPriceTherapySubAccount").innerText =
      finalPrice;
    calculateTotalPrice(
      document.getElementById("totalPriceBloodsSubAccount"),
      document.getElementById("totalPriceAppointmentsSubAccount"),
      document.getElementById("totalPriceTherapySubAccount"),
      document.getElementById("totalPriceSubAccount")
    );
  });
  $(document).on("click", "#finalButton1", function (e) {
    e.preventDefault();
    var equalSign = window.location.search.indexOf("=");
    var idSubAccount = +window.location.search.slice(
      equalSign + 1,
      window.location.search.length
    );
    var commentDiv = document.getElementById("commentAccount1");
    var commentAccount;
    if (![null, undefined].includes(commentDiv)) {
      commentAccount = commentDiv.value;
    } else {
      commentAccount = "";
    }
    var idAccount = +document.querySelector("#accountId").innerText;
    var idDoctor = +document.querySelector("#selectDoctors option:checked")
      .value;
    var idAppointments = Array.from(
      document.querySelectorAll("#mostFinalAppointments option")
    ).map((x) => +x.getAttribute("data-id"));
    var appointmentPrice = +$("#totalPriceAppointmentsSubAccount").text();
    var bloodsPrice = +$("#totalPriceBloodsSubAccount").text();
    var therapyPrice = +$("#totalPriceTherapySubAccount").text();
    var totalPrice = +$("#totalPriceSubAccount").text();
    var idBloods = Array.from(
      document.querySelectorAll("#mostFinalBloods option")
    ).map((x) => +x.getAttribute("data-id"));
    var selector;
    if(document.querySelectorAll("#medicinesSubAccount input") != []) {
      selector = ".single";
    }
    else{
      selector = "#medicinesSubAccount";
    }
    console.log(selector);
    var idMedicines = Array.from(
      document.querySelectorAll(selector +" input")
    ).filter(x=>x.value>0).map((x) => +x.getAttribute("data-idmedicine"));
    var medicineQuantity = Array.from(
      document.querySelectorAll(selector+" input")
    ).map((x) => +x.value);
    medicineQuantity = medicineQuantity.filter((x)=>x>0);
    var idChosenTherapy;
    console.log(idMedicines);
    console.log(medicineQuantity);
    if (document.getElementById("idChosenTherapyId") == undefined) {
      idChosenTherapy = 0;
    } else {
      idChosenTherapy = +document.getElementById("idChosenTherapyId").value;
    }
    
    var idTherapyType = document.getElementById("idTherapyType");
    if (idTherapyType != null) {
      idTherapyType = idTherapyType.value;
    }
    var isSelectedTherapy = true;
    if ([NaN, undefined, null, 0].includes(idTherapyType)) {
      // !NIJE SELEKTOVANA TERAPIJA
      
      isSelectedTherapy = false;
    }
    
    /* var isAnyQuantityZero = medicineQuantity.some((x) => x == 0) ? true : false; */
      if (totalPrice == 0) {
      //alert("IF") ;
      toast("Morate izabrati nešto.", "Greška", "error");
    } else {
      // TODO AJAX
      //alert("ELSE");
      $.ajax({
        type: "POST",
        url: "logic/insertSubAccount.php",
        data: {
          idSubAccount: idSubAccount,
          commentAccount: commentAccount,
          idAccount: idAccount,
          idDoctor: idDoctor,
          idAppointments: idAppointments,
          appointmentPrice: appointmentPrice,
          bloodsPrice: bloodsPrice,
          therapyPrice: therapyPrice,
          totalPrice: totalPrice,
          idBloods: idBloods,
          idMedicines: idMedicines,
          medicineQuantity: medicineQuantity,
          idChosenTherapy: idChosenTherapy,
          idTherapyType: idTherapyType,
          isSelectedTherapy: isSelectedTherapy,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            toast("Uspеšan unos podnaloga.", "Uspešno", "success");
            /* setTimeout(() => {
              window.location.replace(`nalog.php?sort=4`);
            }, 250) */;
          } else if (response == 0 || response == "0") {
            toast("Nedovoljno lekova u magacinu.", "Greška", "error");
          }
        },
      });
    }  
  });
  $(document).on("click", "#finalButtonAp", function (e) {
    e.preventDefault();
    var subAccount = window.location.search.split("&")[1].split("=")[1];
    var commentDiv = document.getElementById("commentAccount1");
    var commentAccount;
    if (![null, undefined].includes(commentDiv)) {
      commentAccount = commentDiv.value;
    } else {
      commentAccount = "";
    }
    var idAccount = +document.querySelector("#accountId").innerText;
    var idDoctor = +document.querySelector("#selectDoctors option:checked")
      .value;
    var idAppointments = Array.from(
      document.querySelectorAll("#mostFinalAppointments option")
    ).map((x) => +x.getAttribute("data-id"));
    var appointmentPrice = +$("#totalPriceAppointmentsSubAccount").text();
    var bloodsPrice = +$("#totalPriceBloodsSubAccount").text();
    var therapyPrice = +$("#totalPriceTherapySubAccount").text();
    var totalPrice = +$("#totalPriceSubAccount").text();
    var idBloods = Array.from(
      document.querySelectorAll("#mostFinalBloods option")
    ).map((x) => +x.getAttribute("data-id"));
    var idMedicines = Array.from(
      document.querySelectorAll("#medicinesSubAccount input")
    ).map((x) => +x.getAttribute("data-idmedicine"));
    var medicineQuantity = Array.from(
      document.querySelectorAll("#medicinesSubAccount input")
    ).map((x) => +x.value);
    var idChosenTherapy;
    if (document.getElementById("idChosenTherapyId") == undefined) {
      idChosenTherapy = 0;
    } else {
      idChosenTherapy = +document.getElementById("idChosenTherapyId").value;
    }
    var idTherapyType = document.getElementById("idTherapyType");
    if (idTherapyType != null) {
      idTherapyType = idTherapyType.value;
    }
    var isSelectedTherapy = true;
    if ([NaN, undefined, null, 0].includes(idTherapyType)) {
      // !NIJE SELEKTOVANA TERAPIJA
      isSelectedTherapy = false;
    }
    /* var isAnyQuantityZero = medicineQuantity.some((x) => x == 0) ? true : false; */
    if (totalPrice == 0) {
      //alert("IF") ;
      toast("Morate izabrati nešto.", "Greška", "error");
    } else {
      // TODO AJAX
      //alert("ELSE");
      $.ajax({
        type: "POST",
        url: "logic/insertSubAccountAp.php",
        data: {
          subAccount: subAccount,
          commentAccount: commentAccount,
          idAccount: idAccount,
          idDoctor: idDoctor,
          idAppointments: idAppointments,
          appointmentPrice: appointmentPrice,
          bloodsPrice: bloodsPrice,
          therapyPrice: therapyPrice,
          totalPrice: totalPrice,
          idBloods: idBloods,
          idMedicines: idMedicines,
          medicineQuantity: medicineQuantity,
          idChosenTherapy: idChosenTherapy,
          idTherapyType: idTherapyType,
          isSelectedTherapy: isSelectedTherapy,
        },
        dataType: "json",
        success: function (response) {
          if (response == 1) {
            toast("Uspеšan unos podnaloga.", "Uspešno", "success");
            /* setTimeout(() => {
              window.location.replace(`adminpanel.php?view=4`);
            }, 250); */
          } else if (response == 0 || response == "0") {
            toast("Nedovoljno lekova u magacinu.", "Greška", "error");
          }
        },
      });
    }
  });
});

function calculateTotalPriceMedicines() {
  var total = 0;
  var medicinePrices = Array.from(
    document.querySelectorAll(".singleMedicinePriceUpdate")
  ).map((x) => +x.innerText);
  medicinePrices.forEach((price) => {
    total += price;
  });
  var totalMedicinesPriceUpdate = document.getElementById(
    "totalMedicinesPriceUpdate"
  );
  totalMedicinesPriceUpdate.innerText = total;
}
function checkRegex(value, regex, error, errorInput, array) {
  if (!regex.test(value)) {
    array.push(error);
    errorInput.html(error);
  }
}
function calculateTotalPrice(
  bloodsPriceDiv,
  appointmentsPriceDiv,
  medicinesPriceDiv,
  totalPriceDiv
) {
  totalPriceDiv.innerText =
    +bloodsPriceDiv.innerText +
    +appointmentsPriceDiv.innerText +
    +medicinesPriceDiv.innerText;
}
function removeSelectedBloods(fromArray, selectedArray, selectDiv, price) {
  var newArray = fromArray.filter((el) => !selectedArray.includes(el));
  var newTotal = 0;
  for (let i = 0; i < newArray.length; i++) {
    newTotal += Number.parseInt($(newArray[i]).attr("data-price"));
    selectDiv.innerHTML += `<option class='removeBlood select-list1-item' data-id='${$(
      newArray[i]
    ).attr("data-id")}' data-price='${$(newArray[i]).attr("data-price")}'>${
      newArray[i].innerText
    }</option>`;
  }
  price.innerText = newTotal;
}
function addRemoveButton() {
  var addRemoveButton = document.getElementById("addRemoveButton");
  addRemoveButton.innerHTML = "<button class='fc' id='x'><svg id='deletebtn' viewBox='0 0 30 30'><path d='M13.81.25c-.13.01-.47.06-.76.1C7.2 1.13 2.31 5.37.75 11.03c-.4 1.49-.52 2.31-.52 3.95-.01 1.64.08 2.32.46 3.79.63 2.45 1.87 4.65 3.63 6.48 2.31 2.41 5.13 3.87 8.52 4.42 1 .17 3.27.16 4.29-.01 2.22-.36 4.26-1.13 6-2.29 3.53-2.35 5.85-5.99 6.5-10.24.16-1.05.16-3.18 0-4.23-.52-3.34-2-6.22-4.41-8.55-2.19-2.11-4.78-3.41-7.9-3.94-.74-.14-2.91-.24-3.51-.16zm6.2 9.34c.35.18.55.51.55.93-.01.18-.04.39-.08.48s-.96 1.02-2.04 2.08l-1.96 1.94 2.03 2c1.78 1.75 2.04 2.04 2.07 2.25.07.38-.03.69-.28.98-.29.32-.63.43-1.02.32-.24-.07-.55-.34-2.29-2.07l-2-2-1.96 2c-1.73 1.76-2 2-2.24 2.07-.75.19-1.45-.47-1.31-1.23.05-.28.21-.46 2.74-3.01l1.29-1.31-1.96-1.96c-2.11-2.13-2.11-2.12-2-2.71.06-.32.46-.72.78-.78.59-.11.58-.11 2.71 2L15 13.53l1.97-1.96c1.08-1.08 2.04-2 2.13-2.03.26-.12.64-.09.91.05z'/></svg></button>";
}
function calculateSum(array) {
  var total = 0;
  for (let i = 0; i < array.length; i++) {
    total += array[i];
  }
  return total;
}
function addRemoveButton1() {
  var addRemoveButton = document.getElementById("addRemoveButton1");
  addRemoveButton.innerHTML = "<button class='fc addRemoveButton1' id='x1'><svg id='deletebtn' viewBox='0 0 30 30'><path d='M13.81.25c-.13.01-.47.06-.76.1C7.2 1.13 2.31 5.37.75 11.03c-.4 1.49-.52 2.31-.52 3.95-.01 1.64.08 2.32.46 3.79.63 2.45 1.87 4.65 3.63 6.48 2.31 2.41 5.13 3.87 8.52 4.42 1 .17 3.27.16 4.29-.01 2.22-.36 4.26-1.13 6-2.29 3.53-2.35 5.85-5.99 6.5-10.24.16-1.05.16-3.18 0-4.23-.52-3.34-2-6.22-4.41-8.55-2.19-2.11-4.78-3.41-7.9-3.94-.74-.14-2.91-.24-3.51-.16zm6.2 9.34c.35.18.55.51.55.93-.01.18-.04.39-.08.48s-.96 1.02-2.04 2.08l-1.96 1.94 2.03 2c1.78 1.75 2.04 2.04 2.07 2.25.07.38-.03.69-.28.98-.29.32-.63.43-1.02.32-.24-.07-.55-.34-2.29-2.07l-2-2-1.96 2c-1.73 1.76-2 2-2.24 2.07-.75.19-1.45-.47-1.31-1.23.05-.28.21-.46 2.74-3.01l1.29-1.31-1.96-1.96c-2.11-2.13-2.11-2.12-2-2.71.06-.32.46-.72.78-.78.59-.11.58-.11 2.71 2L15 13.53l1.97-1.96c1.08-1.08 2.04-2 2.13-2.03.26-.12.64-.09.91.05z'/></svg></button>";
}
function selectMultipleWithoutHoldingCTRL(select) {
  $(select)
    .mousedown(function (e) {
      e.preventDefault();
      var select = this;
      var scroll = select.scrollTop;
      e.target.selected = !e.target.selected;
      setTimeout(function () {
        select.scrollTop = scroll;
      }, 0);
      $(select).focus();
    })
    .mousemove(function (e) {
      e.preventDefault();
    });
}
function exit() {
  window.location.replace("kartoni.php");
}
function exitexit() {
  $("#modalTherapy").html("");
  window.location.replace("nalog.php?sort=4");
}
function exitexitexit() {
  window.location.replace("adminpanel.php?view=4");
}
function dates() {
  var dateStart = document.getElementById("dateStart").value;
  var dateEnd = document.getElementById("dateEnd").value;
  if (dateStart == "") dateStart = "1970-01-01";
  var today = new Date();
  var date =
    today.getFullYear() +
    "-" +
    (today.getMonth() + 1) +
    "-" +
    (today.getDate() + 1);
  if (dateEnd == "") dateEnd = date;
  if (window.location.search == "") {
    window.location.replace(
      `nalog.php?dateStart=${dateStart}&dateEnd=${dateEnd}`
    );
  } else {
    if (window.location.search.includes("sort")) {
      var indexOfSort = window.location.search.indexOf("sort");
      var sort = window.location.search[indexOfSort + 5];
      window.location.replace(
        `nalog.php?dateStart=${dateStart}&dateEnd=${dateEnd}&sort=${sort}`
      );
    } else {
      window.location.replace(
        `nalog.php?dateStart=${dateStart}&dateEnd=${dateEnd}`
      );
    }
  }
}
function resetButton(e) {
  e.preventDefault();
  window.location.replace("nalog.php?sort=4");
}
function isFutureDate(value) {
  d_now = new Date();
  d_inp = new Date(value);
  return d_now.getTime() <= d_inp.getTime();
}
function toast(message, h, icon) {
  $.toast({
    heading: h,
    text: `<p>${message}</p>`,
    showHideTransition: "fade",
    icon: icon,
  });
}