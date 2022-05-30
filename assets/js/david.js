$(function () {
    print(".buttonForPrintSubFull", "printSub");
    print(".buttonForPrintSubA4", "printSubA4");
    print(".buttonForPrintSubSliper", "printSubSliper");
    print(".buttonForPrintFullAll", "printAll");
    print(".buttonForPrintA4customer", "printa4customer");
    print(".buttonForPrintA4All", "printAllA4");
    print(".buttonForPrintSliperAll", "printAllSliper");
    print(".buttonForPrintDrIzvestaj", "printDr");
    /* $(document).on("click", ".printButtonSubAcc", function (e) {
        e.preventDefault();
        console.log("KLIK");
        var idAccount = $(this).attr("data-id");
        window.location.replace(`nalog.php?printSubAccount=${idAccount}`);
    }); */
    $(document).on("click", ".printButtonAccAll", function (e) {
        e.preventDefault();
        var idAccount = $(this).attr("data-id");
        window.location.replace(`nalog.php?printAccountAll=${idAccount}`);
    });
    $(document).on("click", ".printButtonAccAllEmployee", function (e) {
        e.preventDefault();
        var idAccount = $(this).attr("data-id");
        window.location.replace(`adminpanel.php?view=4&printAccountAll=${idAccount}`);
    });
    $(document).on("click", ".checkLabel", function (e) {
        e.preventDefault();
        //console.log();
        $(this).prev().click();
    });
    $(document).on("click", ".showUpdatePatient", function (e) {
        e.preventDefault();
        $('#updatePatientDiv').slideToggle();
        var idPatient = $(this).attr("data-id");
        var updateDiv = document.getElementById("updatePatientDiv");

        
        //console.log(idPatient);
        $.ajax({
            type: "POST",
            url: "logic/showPatients.php",
            data: {
                idPatient: idPatient,
            },
            dataType: "json",
            success: function (response) {
                if (response == 0) {
                    updateDiv.innerHTML = "<h4>Došlo je do greške</h4>";
                } else {
                    if(response["email"] == null || response["email"] == 0) response["email"] = "";
                    if(response["comment"] == null || response["comment"] == 0) response["comment"] = "";
                    updateDiv.innerHTML = `
          <form class="open-cardboard" id="updatePatient">

          <div class="open-cardboard-flex container100 fconly">
              <div class="container100">
                  <div class="cardboard-flex flex">
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="nameUpdate">Ime</label>
                          <input class="input-sec" data-id='${response["id_patient"]}' value=${response["name"]} type="text" id="nameUpdate" name="nameUpdate"><span class="error-msg" id="nameErrorUpdate"></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="parentNameUpdate">Ime roditelja</label>
                          <input class="input-sec" value='${response["parent_name"]}' type="text" id="parentNameUpdate" name="parentNameUpdate"><span class="error-msg" id="parentNameErrorUpdate"></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="surnameUpdate">Prezime</label>
                          <input class="input-sec" value='${response["surname"]}' type="text" id="surnameUpdate" name="surnameUpdate"><span class="error-msg" id="surnameErrorUpdate"></span>
                      </div>
                  </div>

                  <div class="cardboard-flex flex">
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="dateOfBirthUpdate">Datum rodjenja</label>
                          <input class="input-sec" value=${response["date_of_birth"]} type="date" class="dateOfBirthUpdate" id="dateOfBirthUpdate" name="dateOfBirthUpdate"><span class='error-msg' id='dateErrorUpdate'></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="jmbgUpdate">JMBG</label>
                          <input class="input-sec" value='${response["jmbg"]}' type="text" id="jmbgUpdate" name="jmbgUpdate"><span class="error-msg" id="jmbgErrorUpdate"></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="identificationNumberUpdate">Broj licne karte</label>
                          <input class="input-sec" value='${response["identificaton_number"]}' type="text" id="identificationNumberUpdate" name="identificationNumberUpdate"><span class="error-msg" id="identificationNumberErrorUpdate"></span>
                      </div>
                  </div>

                  <div class="cardboard-flex flex">
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="emailUpdate">Email</label>
                          <input class="input-sec" value='${response["email"]}' type="text" id="emailUpdate" name="emailUpdate"><span class="error-msg" id="emailErrorUpdate"></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="phoneUpdate">Telefon</label>
                          <input class="input-sec" value='${response["phone"]}' type="text" id="phoneUpdate" name="phoneUpdate"><span class="error-msg" id="phoneErrorUpdate"></span>
                      </div>
                      <div class="container100 fdcolumn mauto mr10">
                          <label class="label-classic" for="addressUpdate">Adresa</label>
                          <input class="input-sec" value='${response["address"]}' type="text" id="addressUpdate" name="addressUpdate"><span class="error-msg" id="addressErrorUpdate"></span>
                      </div>
                  </div>
              </div>

              <div class="container100 comment fdcolumn2">
                  <label class="label-classic" for="commentPatientUpdate">Komentar</label>
                  <textarea class="comment-area scroll" id="commentPatientUpdate" name="commentPatientUpdate">${response["comment"]}</textarea>
              </div>

          </div>

      </form>
          <div class="button-pacijent fc mt20">
            <input type="button" class="btn-22 btn wght600 fs20 poppins updateClass" id="updatePatientButton" value="IZMENI PACIJENTA">
          </div>
          `;
                    
                }
            },
        });
       
    });
    
    $(document).on("click", ".seeAccounts", function (e) {
        e.preventDefault();
        console.log("klik nalog");
        var employeeDiv = $("#employeeAccounts");
        employeeDiv.html("");
        var id = +$(this).attr("data-id");
        console.log(id);
        $.ajax({
            type: "GET",
            url: "logic/getEmployeeAccounts.php",
            data: {
                id: id,
            },
            dataType: "json",
            success: function (response) {
                employeeDiv.html(response);
            },
        });
    });
    $(document).on("click", ".seeSubAccounts", function (e) {
        e.preventDefault();
        console.log("klikPodnalog");
        var employeeDiv = $("#employeeAccounts");
        employeeDiv.html("");
        var id = +$(this).attr("data-id");
        console.log(id);
        $.ajax({
            type: "GET",
            url: "logic/getEmployeeSubAccounts.php",
            data: {
                id: id,
            },
            dataType: "json",
            success: function (response) {
                employeeDiv.html(response);
            },
        });
    });
    $(document).on("click", ".seeChecked", function (e) {
        e.preventDefault();
        var employeeDiv = $("#employeeAccounts");
        employeeDiv.html("");
        var id = +$(this).attr("data-id");
        console.log(id);
        $.ajax({
            type: "GET",
            url: "logic/getCheckedAccounts.php",
            data: {
                id: id,
            },
            dataType: "json",
            success: function (response) {
                employeeDiv.html(response);
                
            },
        });
    });
    $(document).on("click", "#seeAccountsEmployee", function (e) {
        e.preventDefault();
        div = $("#outputAccounts");
        $.ajax({
            type: "GET",
            url: "logic/seeAccountsEmployees.php",
            data: {},
            dataType: "json",
            success: function (response) {
                div.html(response);
                
            },
        });
    });
    $(document).on("click", "#seeSubAccountsEmployee", function (e) {
        e.preventDefault();
        div = $("#outputAccounts");
        $.ajax({
            type: "GET",
            url: "logic/seeSubAccountsEmployees.php",
            data: {},
            dataType: "json",
            success: function (response) {
                div.html(response);
                
            },
        });
    });
    $(document).on("click", "#deletedAccounts", function (e) {
        e.preventDefault();
        div = $("#deletedContent");
        div.html("");
        console.log("idemo");
        $.ajax({
            type: "GET",
            url: "logic/showDeletedAccounts.php",
            data: {},
            dataType: "json",
            success: function (response) {
                if (response) {
                    div.html(response);
                    
                } else {
                    toast("Nema nijedan izbrisan nalog", "Greška", "error");
                }
            },
        });
    });
    $(document).on("click", "#deletedSubAccounts", function (e) {
        e.preventDefault();
        div = $("#deletedContent");
        div.html("");
        console.log("idemo");
        $.ajax({
            type: "GET",
            url: "logic/showDeletedSubAccounts.php",
            data: {},
            dataType: "json",
            success: function (response) {
                if (response) {
                    div.html(response);
                    
                } else {
                    toast("Nema nijedan izbrisan nalog", "Greška", "error");
                }
            },
        });
    });
    var array = [];
    $(document).on("click", ".analysisPrice", function (e) {
        var total = 0;
        e.preventDefault();
        var calc = document.getElementById("calcInfo");
        var analysisPrice = +$(this).attr("data-price");
        var analysisName = $(this).text();
        array.push(analysisPrice);
        console.log(array);
        console.log(analysisName, analysisPrice);
        calc.innerHTML += `<div class='calcfc fstart'><div class="analysisCalc">
            <span class="analysisNameCalc scroll2">${analysisName}</span><span class="analysisPriceCalc">${analysisPrice}</span>
            <span class='removeFirstAddition' data-price='${analysisPrice}'>&times;</span>
        </div></div>`;
        array.forEach((x)=>{
            total += x;
        })
        totalDiv = $("#total");
        totalDiv.html(total);
    });
    $(document).on("click", ".medicinePrice", function (e) {
        var total = 0;
        e.preventDefault();
        var calc = document.getElementById("calcInfo");
        var medicinePrice = +$(this).attr("data-price");
        var analysisName = $(this).text();
        array.push(medicinePrice);
        // console.log(array);
        // console.log(analysisName, medicinePrice);
        calc.innerHTML += `<div class='calcfc fstart'><div class="analysisCalc">
            <span class="analysisNameCalc scroll2">${analysisName}</span><span class="medicinePriceCalc">${medicinePrice}</span>
            <span class='removeFirstAddition' data-price='${medicinePrice}'>&times;</span>
        </div></div>`;
        array.forEach((x)=>{
            total += x;
        })
        totalDiv = $("#total");
        totalDiv.html(total);
    });
    $(document).on("click", "#clear", function (e) {
        e.preventDefault();
        array=[];
        total = 0;
        totalDiv = $("#total");
        totalDiv.html("");
        $("#calcInfo").html("");
        
    });
    $(document).on("click", ".removeFirstAddition", function (e) {
        e.preventDefault();
        var total = 0;
        var analysisPrice = +$(this).attr("data-price");
        var indexOf = array.indexOf(analysisPrice);
        console.log(indexOf);
        array = removeFromArray(array, analysisPrice);
        console.log(array);
        //console.log($(this).parent());
        
        array.forEach((x)=>{
            total += x;
        })
        totalDiv = $("#total");
        totalDiv.html(total);
        $(this).parent().remove();

    });
    $(document).on("click", ".appointmentPrice", function (e) {
        var total = 0;
        e.preventDefault();
        var calc = document.getElementById("calcInfo");
        var appointmentPrice = +$(this).attr("data-price");
        var appointmentName = $(this).text();
        array.push(appointmentPrice);
        console.log(array);
        console.log(appointmentName, appointmentPrice);
        calc.innerHTML += `<div class='calcfc fstart'><div class="analysisCalc">
            <span class="analysisNameCalc scroll2">${appointmentName}</span><span class="appointmentPriceCalc">${appointmentPrice}</span>
            <span class='removeFirstAddition' data-price='${appointmentPrice}'>&times;</span>
        </div></div>`;
        
        array.forEach((x)=>{
            total += x;
        })
        totalDiv = $("#total");
        totalDiv.html(total);
        
    });
    $(document).on("click",".updateAppointmentButton", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var tag = $(this).parent().parent().children().eq(2).children().eq(0);
        var newPrice = tag.val();
        console.log(newPrice);
        if(newPrice == 0|| newPrice == "" || newPrice == NaN || newPrice == undefined){
            toast("Morate uneti vrednost", "Greška", "error");
        }

        else{
            $.ajax({
                type: "POST",
                url: "logic/updateAppointmentPrice.php",
                data: {
                    id: id,
                    newPrice: newPrice
                },
                dataType: "json",
                success: function (response) {
                    
                    console.log(tag.parent().prev());
                    tag.parent().prev().text(response["price"]);
                }
            });
        }
        
    });
    $(document).on("click",".updateAnalysisButton", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var tag = $(this).parent().parent().children().eq(2).children().eq(0);
        var newPrice = tag.val();
        if(newPrice == 0|| newPrice == "" || newPrice == NaN || newPrice == undefined){
            toast("Morate uneti vrednost", "Greška", "error");
        }
        else{
            $.ajax({
                type: "POST",
                url: "logic/updateAnalysisPrice.php",
                data: {
                    id: id,
                    newPrice: newPrice
                },
                dataType: "json",
                success: function (response) {
                    
                    console.log(tag.parent().prev());
                    tag.parent().prev().text(response["price"]);
                }
            });
        }
        
        
    });
    $(document).on("click", ".copyClass",function (e) {
        e.preventDefault();
        console.log("klik");
        var copyText = $(this).children(1).text();
        copyToClipboard(copyText);
        toast("Uspešno kopiran JMBG.", "JMBG", "success");
        
    });
    $(document).on("click",".updateMedicineButton", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var tag = $(this).parent().parent().children().eq(2).children().eq(0);
        var newPrice = tag.val();

        if(newPrice == 0|| newPrice == "" || newPrice == NaN || newPrice == undefined){
            toast("Morate uneti vrednost", "Greška", "error");
        }
        else{

            $.ajax({
                type: "POST",
                url: "logic/updateMedicinePrice.php",
                data: {
                    id: id,
                    newPrice: newPrice
                },
                dataType: "json",
                success: function (response) {
                    
                    console.log(tag.parent().prev());
                    tag.parent().prev().text(response["price"]);
                }
            });
        }
        
        
    });
});
function removeFromArray(arr, value) {
    var index = arr.indexOf(value);
    if (index > -1) {
      arr.splice(index, 1);
    }
    return arr;
}

function totalFunction(array, div){
    let total = 0;
    array.forEach((x)=>{
        total +=x;
    })
    div.html(total);
    

}
function print(klass, page) {
    $(document).on("click", klass, function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        window.open(`${page}.php?idAcc=${id}`);
    });
}
function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}
