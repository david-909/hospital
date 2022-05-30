$(document).on("click", ".updateButtonAcc1", function (e) {
  e.preventDefault();
  var pageLocation = window.location.pathname;  
  pageLocation = pageLocation.split("/");
  pageLocation = pageLocation[pageLocation.length-1];
  console.log(pageLocation);
  var idSubAccount = $(this).attr("data-id");
  if(pageLocation == "adminpanel.php"){

    window.location.replace(`adminpanel.php?view=4&idSubAccount=${idSubAccount}`);
  }
  else if(pageLocation == 'nalog.php'){
    window.location.replace(`nalog.php?sort=4&idSubAccount=${idSubAccount}`)
  }
  
});
$(document).on("click", ".deleteButtonAcc1", function (e) {
  e.preventDefault();
  var id = $(this).attr("data-id");
  $.ajax({
    type: "GET",
    url: "logic/isShownAccount1.php",
    data: {
      id: id,
    },
    dataType: "json",
    success: function (response) {
      if (response == 1) {
        alert("Uspesno izbrisan");
        window.location.replace("nalog.php?sort=4");
      } else {
        alert("Doslo je do greske");
        window.location.reload();
      }
    },
  });
});
$(document).on("input", "#searchAccounts", function (e) {
  var showAccountsDiv = document.getElementById("showAccountsDiv");
  var input = document.getElementById("searchAccounts").value;
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var sort = urlParams.get("sort");
  var dateStart = urlParams.get("dateStart");
  var dateEnd = urlParams.get("dateEnd");
  if (input.length > 0) {
    $.ajax({
      type: "GET",
      url: "logic/showAccountsFilter.php",
      data: {
        input: input,
        dateStart: dateStart,
        dateEnd: dateEnd,
        sort: sort,
      },
      dataType: "json",
      success: function (response) {
        showAccountsDiv.innerHTML = response;
      },
    });
  } else {
    $.ajax({
      type: "GET",
      url: "logic/showAccounts.php",
      data: {
        dateStart: dateStart,
        dateEnd: dateEnd,
        sort: sort,
      },
      dataType: "json",
      success: function (response) {
        showAccountsDiv.innerHTML = response;
      },
    });
  }
});
$(document).on("click", "#updateButton1", function (e) {
  e.preventDefault();
  var idAccount = +$(this).attr("data-idAccount");
  var idSubAccount = $(this).attr("data-idSubAccount");
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
  var totalPrice = +$("#totalAccountPriceUpdate").text();
  var commentUpdate = $("#commentUpdate").val();
  $.ajax({
    type: "POST",
    url: "logic/updateAccount1.php",
    data: {
      commentUpdate: commentUpdate,
      idAccount: idAccount,
      idSubAccount: idSubAccount,
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
        toast("Uspešno izmenjen podnalog.", "Uspešno","success");
        // window.location.replace("nalog.php?sort=4");
      } else if (response == 5) {
        toast("Nedovoljno lekova u magacinu.", "Greška", "error");
      } else if (response == 0)
        toast("Izaberite neke vrednosti kako biste ih promenili.", "Greška", "error");
    },
  });
});
/* $(document).on("blur", "#searchAnalysis", function () {
  setTimeout(() => {
    document.getElementById("priceCard").innerHTML = "";
  }, 250);
}); */
/* $(document).on("blur", ".blured", function () {
  setTimeout(() => {
    document.getElementById("priceCard").innerHTML = "";
  }, 0);
}); */
var modalTherapy = document.getElementById("modalTherapy");
var modalTherapyBody = document.getElementById("modalTherapy-body");
$(document).on("click", ".btnTherapyAccount", function (e) {
  e.preventDefault();
  showModalTherapy();
  var idAccount = +$(this).attr("data-idAccount");

  $.ajax({
    type: "GET",
    url: "logic/showModalTherapiesAccount.php",
    data: {
      idAccount: idAccount,
    },
    dataType: "json",
    success: function (response) {
      modalTherapyBody.innerHTML = response;
    },
  });
});
$(document).on("click", ".btnTherapySubAccount", function (e) {
  e.preventDefault();
  showModalTherapy();
  var idSubAccount = +$(this).attr("data-idSubAccount");
  
   $.ajax({
     type: "GET",
     url: "logic/showModalTherapiesSubAccount.php",
     data: { idSubAccount: idSubAccount },
     dataType: "json",
     success: function (response) {
       modalTherapyBody.innerHTML = response;
     },
   });
});
$(document).on("click", "#exitView", function (e) {
  e.preventDefault();
  modalTherapy.style.display = "none";
});
$(document).on("click", "#showIncomeButton", function (e) {
  var startDate = document.getElementById("bankDateStart").value;
  var endDate = document.getElementById("bankDateEnd").value;
  if (
    isFutureDate(startDate) ||
    startDate == "" ||
    endDate == "" ||
    isFutureDate(endDate)
  ) {
    document.getElementById("bankDateDiv").innerHTML =
      "<h4>Izaberite validan datum.";
  } else {
    $.ajax({
      type: "POST",
      url: "logic/bankDate.php",
      data: { startDate: startDate, endDate: endDate },
      dataType: "json",
      success: function (response) {
        document.getElementById("bankDateDiv").innerHTML = response;
      },
    });
  }
});
function showModalTherapy() {
  modalTherapyBody.innerHTML = "";
  modalTherapy.style.display = "block";
  modalTherapy.style.zIndex = 9999;
}
function isFutureDate(value) {
  d_now = new Date();
  d_inp = new Date(value);
  return d_now.getTime() <= d_inp.getTime();
}
