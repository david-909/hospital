
// * OPEN/CLOSE SIDE NAV
function openSideNav() {
    document.getElementById("sidenav").style.width = "210px";
    document.getElementById("main").style.marginRight = "-5000px";
    document.getElementById("main").style.transition = "ease-in-out .36s";
    document.getElementById("closeSidebtn").style.transition = "ease in out 1s";
    document.getElementById("account").style.marginRight = "100px";
    document.getElementById("account").style.transition = "ease-in-out .46s";
}
function closeSideNav() {
    document.getElementById("sidenav").style.width = "0";
    document.getElementById("account").style.marginRight = "0px";
    document.getElementById("main").style.marginRight = "0px";
    document.getElementById("closeSidebtn").style.transition = "ease-out 1s";
}



$(function () {
  $(".profile").on("click", function (e) {
    $(".acc-menu").toggleClass("menu-active");
    e.stopPropagation()
  });
});
$(function () {
  $(".nolink").on("click", function (e) {
    $(".accordion4-body").toggleClass("menu-active");
    e.stopPropagation()
  });
  $(document).on("click", function(e) {
    if ($(e.target).is(".accordion4-body") === false) {
      $(".accordion4-body").removeClass("menu-active");
    }
  });
});


/* // * NAV (NALOZI TAB) TOGGLE
function accmenuToggle2() {
    const toggleMenu = document.querySelector('.accordion4-body');
    toggleMenu.classList.toggle('menu-active')
} */


// * ACCORDION
var accordion3 = (function () {
    $(document).ready(function () {
        $(".js-accordion3-header").one('click', function (event) {
            event.preventDefault();
            $(this).prop('disabled', true);
        });
    });

    $('.accordion5-header').on('click', () => $('.arrow').toggleClass('rotated'));
    $('.accordion5-header').on('click', () => $('.accordion5-header').toggleClass('activeacc'));
    $('.accordion5-header.activeacc').removeClass('activeacc');
    $(this).toggleClass('activeacc');




    var $accordion3 = $('.js-accordion3');
    var $accordion3_header = $accordion3.find('.js-accordion3-header');
    var $accordion3_item = $('.js-accordion3-item');

    // default settings 
    var settings = {
        // animation speed
        speed: 300,

        // close all other accordion3 items if true
        oneOpen: true
    };

    return {
        // pass configurable object literal
        init: function ($settings) {
            $accordion3_header.on('click', function () {
                accordion3.toggle($(this));
            });

            $.extend(settings, $settings);

            // ensure only one accordion3 is active if oneOpen is true
            if (settings.oneOpen && $('.js-accordion3-item.active').length > 1) {
                $('.js-accordion3-item.active:not(:first)').removeClass('active');
            }

            // reveal the active accordion3 bodies
            $('.js-accordion3-item.active').find('> .js-accordion3-body').show();
        },
        toggle: function ($this) {

            if (settings.oneOpen && $this[0] != $this.closest('.js-accordion3').find('> .js-accordion3-item.active > .js-accordion3-header')[0]) {
                $this.closest('.js-accordion3')
                    .find('> .js-accordion3-item')
                    .removeClass('active')
                    .find('.js-accordion3-body')
                    .slideUp()
            }

            // show/hide the clicked accordion3 item
            $this.closest('.js-accordion3-item').toggleClass('active');
            $this.next().stop().slideToggle(settings.speed);
        }
    }
})();

$(document).ready(function () {
    accordion3.init({
        speed: 200,
        oneOpen: false
    });
});


// * CHANGE QUANTITY OF MAGACINE
$(".minus").click(function () {
    this.parentNode.querySelector('input[type=number]').stepDown();
});
$(".plus").click(function (e) {
    this.parentNode.querySelector('input[type=number]').stepUp();
});


// * DUPLICATE ACCOUNT PLUS BUTTON
$('.crudbtns #addplus').click(function() {
    /* $('.crudbtns #addplus').css('transform', 'rotate(-180deg)'); */
    var $button = $(this).addClass('activeaddplus');
    $('.crudbtns #addplus').not($button).removeClass('activeaddplus');
});


// * IZMENA CENA ACTIVE TOGGLE
$('.cenecene .openprice').click(function() {
    /* $('.crudbtns #addplus').css('transform', 'rotate(-180deg)'); */
    var $button = $(this).addClass('activecena');
    $('.cenecene .openprice').not($button).removeClass('activecena');
});


// * THERAPY ACTIVE TOGGLE
$('#therapies button').click(function() {
    /* $('.crudbtns #addplus').css('transform', 'rotate(-180deg)'); */
    var $button = $(this).addClass('activecustom');
    $('#therapies button').not($button).removeClass('activecustom');
});

// * RESET BUTTON ANIME
$('#resetButton').click(function() {
    /* $('.crudbtns #addplus').css('transform', 'rotate(-180deg)'); */
    var $button = $(this).toggleClass('activereload');
    $('#resetButton').not($button).removeClass('activeareload');
    /*  var $tab = $('#' + $(this).data('tab')).toggle();
     $('.closable_box').not($tab).hide(); */
});


// * SELECT LIST OPTION FRONTEND
// !! ISTRAZITI
$('.selectlist').each(function () {

    // Cache the number of options
    var $this = $(this),
        numberOfOptions = $(this).children('option').length;

    // Hides the select element
    $this.addClass('s-hidden');

    // Wrap the select element in a div
    $this.wrap('<div class="head-tcss selectlist"></div>');

    // Insert a styled div to sit over the top of the hidden select element
    $this.after('<div class="styledSelect"></div>');

    // Cache the styled div
    var $styledSelect = $this.next('.styledSelect');

    // Show the first select option in the styled div
    $styledSelect.text($this.children('option').eq(0).text());

    // Insert an unordered list after the styled div and also cache the list
    var $list = $('<ul />', {
        'class': 'options'
    }).insertAfter($styledSelect);

    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        $('<li/>', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function (e) {
        e.stopPropagation();
        $('.styledSelect.active33').each(function () {
            $(this).removeClass('active33').next('ul.options').hide();
        });
        $(this).toggleClass('active33').next('ul.options').toggle();
    });
    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function (e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active33');
        $this.val($(this).attr('rel'));
        $list.hide();
        /* alert($this.val()); Uncomment this for demonstration! */
    });

    // Hides the unordered list when clicking outside of it
    $(document).click(function () {
        $styledSelect.removeClass('active33');
        $list.hide();
    });
    $(document).click(function () {
        $('.styleSelect').removeClass('active33');
        $list.hide();
    });
});

$('.addprice-btn').on('click', function(e) {
    $('.firstTr').toggleClass("activeprice"); //you can list several class names 
    e.preventDefault();
});



// * Select toggle class
/* $('option.removeAppointment').on('click', function(e) {
    $('option.removeAppointment').toggleClass("activelist2"); //you can list several class names 
    e.preventDefault();
}); */




// * SELECT LIST OPTION FRONTEND
var x, i, j, l, ll, selElmnt, a, b, c;
/* Look for any elements with the class "custom-select": */
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    ll = selElmnt.length;
    /* For each element, create a new DIV that will act as the selected item: */
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    $(this).parent().css('z-index', 9999);
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /* For each element, create a new DIV that will contain the option list: */
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < ll; j++) {
        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");
        c.setAttribute("class", "options");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function (e) {

            /* When an item is clicked, update the original select box,
            and the selected item: */
            var y, i, k, s, h, sl, yl;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            sl = s.length;
            h = this.parentNode.previousSibling;
            for (i = 0; i < sl; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    yl = y.length;
                    for (k = 0; k < yl; k++) {
                        y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            h.click();
        });
        b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function (e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}

function closeAllSelect(elmnt) {
    /* A function that will close all select boxes in the document,
    except the current select box: */
    var x, y, i, xl, yl, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    xl = x.length;
    yl = y.length;
    for (i = 0; i < yl; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active");
        }
    }
    for (i = 0; i < xl; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
        }
    }
}
$(function () {
    $(".select-selected").on("click", function (e) {
        $(".select-selected").toggleClass("activeaccordion");
    });
    $(document).on("click", function (e) {
        if ($(e.target).is(".select-selected") === false) {
            $(".select-selected").removeClass("activeaccordion");
        }
    });
});

document.addEventListener("click", closeAllSelect);




// This is the important part!
function collapseSection(element) {
    // get the height of the element's inner content, regardless of its actual size
    var sectionHeight = element.scrollHeight;

    // temporarily disable all css transitions
    var elementTransition = element.style.transition;
    element.style.transition = '';

    // on the next frame (as soon as the previous style change has taken effect),
    // explicitly set the element's height to its current pixel height, so we 
    // aren't transitioning out of 'auto'
    requestAnimationFrame(function() {
      element.style.height = sectionHeight + 'px';
      element.style.transition = elementTransition;

      // on the next frame (as soon as the previous style change has taken effect),
      // have the element transition to height: 0
      requestAnimationFrame(function() {
        element.style.height = 0 + 'px';
      });
    });

    // mark the section as "currently collapsed"
    element.setAttribute('data-collapsed', 'true');
}
function expandSection(element) {
  // get the height of the element's inner content, regardless of its actual size
  var sectionHeight = element.scrollHeight;
  // have the element transition to the height of its inner content
  element.style.height = sectionHeight + 'px';
  // when the next css transition finishes (which should be the one we just triggered)
  element.addEventListener('transitionend', function(e) {
    // remove this event listener so it only gets triggered once
    element.removeEventListener('transitionend', arguments.callee);
    // remove "height" from the element's inline styles, so it can return to its initial value
    element.style.height = null;
  });
  // mark the section as "currently not collapsed"
  element.setAttribute('data-collapsed', 'false');
}
var a = document.querySelector('#add-account-expand-button')
if (a != undefined) {
  a.addEventListener('click', function(e) {
    var section = document.querySelector('.section.collapsible');
    expandSection(section)
    section.setAttribute('data-collapsed', 'false');
  });
}
var b = document.querySelector('#add-account-collapse-button')
if (b != undefined) {
  b.addEventListener('click', function(e) {
    var section = document.querySelector('.section.collapsible');
    collapseSection(section)
  });
}
$('.calculator-options #add-account-collapse-button').hide();
$('.calculator-options #add-account-expand-button').click(function (e) { 
  e.preventDefault();
  $('.calculator-options #add-account-expand-button').hide();
  $('.calculator-options .close-button').show();
});
$('.calculator-options .close-button').click(function (e) { 
  e.preventDefault();
  $('.calculator-options .close-button').hide();
  $('.calculator-options #add-account-expand-button').show();
});

$('.rnalog-head #add-account-expand-button').click(function (e) { 
  e.preventDefault();
  $('.rnalog-head #add-account-expand-button').hide();
  $('.add-nalog .close-button').show();
});
$('.add-nalog .close-button').click(function (e) { 
  e.preventDefault();
  $('.add-nalog .close-button').hide();
  $('.rnalog-head #add-account-expand-button').show();
});
/* 
function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

 */


/* $(".removeallvalues-print").on("click", function () {
    $('.value-print').val('');
    $('.value-print').val('');
    $('.value-print').val('');
}); */






/* $(".removeallvalues-print").click(function(e) {
    e.preventDefault();
    if (clicks5 == 0){
        $('.value-print').hide();
        $('.currency-print').hide();
        $('.value-print').hide();
    } else{
        $('.value-print').show();
        $('.currency-print').show();
        $('.value-print').show();
    }
    ++clicks5;
}); */

// ! OBRISI CENE LEKOVA */
/* $('.print-settings button').click(function() {
    var $button = $(this).addClass('activeprint');
    $('.print-settings button').not($button).removeClass('activeprint');
}); */

/* $(".customer-print").click(function(e) {
    e.preventDefault();
    $('.date-print').toggleClass('chide');
    $('.service2-print').toggleClass('chide');
    $('.h3cena-print').toggleClass('chide');
    $('.header-print').toggleClass('chide');
    $('.printTherapy').toggleClass('therapycenter');
    $('.price-print').toggleClass('chide');
    $('.ukupnoprint').toggleClass('chide');
    $('.headcomment').toggleClass('chide');
    $('.printCommentAccount-edit-print').toggleClass('chide');
    $('.chide-btn').toggleClass('chide-btn-active');
    $('.phide-btn').toggleClass('chide-btn-active');
  });

$(".chide-btn").click(function(e) {
    e.preventDefault();
    $('.date-print').toggleClass('chide');
});
$(".phide-btn").click(function(e) {
    e.preventDefault();
    $('.service2-print').toggleClass('chide');
});   */

/* $(".customer-print").click(function(e) {
    e.preventDefault();
    var clicks5 = $(this).data('clicks');
    if (clicks5) {
        $('.service2-print').toggleClass('chide');

    } else {
        
    }
    $(this).data("clicks", !clicks5);
  }); */


/*   $('.printnow').on('click', function () {
    displayTheData();
    
 })
 function displayTheData() {
    $(document).ready(function () {
       $("#printTheDivisionValue").html($(".allInfo").html());
    })}; */


    $(".customer-print").click(function(e) {
        e.preventDefault();
        $('.customer-print').toggleClass('activeprint');
        $('.service2-print').toggleClass('chide');
        $('.h3cena-print').toggleClass('chide');
        $('.header-print').toggleClass('chide');
        $('.printTherapy').toggleClass('therapycenter');
        $('.price-print').toggleClass('chide');
        $('.ukupnoprint').toggleClass('chide');
        $('.headcomment').toggleClass('chide');
        $('.printCommentAccount-edit-print').toggleClass('chide');
        $('.service2-print span').show();

        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            $('.customer-print').removeClass('activeprint');  
        } else {
            
        }
        $(this).data("clicks", !clicks5);
      });

      $(".appblprice-print").click(function(e) {
        e.preventDefault();
        $('.appblprice-print').toggleClass('activeprint');
        $('.master-print .appbl-print').toggleClass('chide');
        $('.h3cena-print2').toggleClass('chide');
        $('.header-print').toggleClass('chide');
        $('#printAppointmentPriceTotal').toggleClass('chide')
        $('#printAppointmentPriceTotal').toggleClass('chide');
        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });



      $(".pregledi-print").click(function(e) {
        e.preventDefault();
        
        $('.pregledi-print').toggleClass('activeprint');
        $('.service2-print input').toggleClass('chide');
        $('.service2-print span').toggleClass('chide');
        $('.hideallq').toggleClass('chide');
        $('.appbl-print input').toggleClass('chide');

        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      $(".onlymedicine-print").click(function(e) {
        e.preventDefault();
        
        $('.onlymedicine-print').toggleClass('activeprint');
       /*  $('.service2-print input').toggleClass('chide');
        $('.service2-print span').toggleClass('chide');
        $('.hideallq').toggleClass('chide');
        $('.appbl-print input').toggleClass('chide'); */
        $('.printTherapy').toggleClass('therapycenter');
        $('.therapy-prices .tab-print .service2-print').toggleClass('chide');
        $('.h3therapy').toggleClass('chide');
        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      $(".therapy-print").click(function(e) {
        e.preventDefault();
        
        $('.therapy-print').toggleClass('activeprint');
       /*  $('.service2-print input').toggleClass('chide');
        $('.service2-print span').toggleClass('chide');
        $('.hideallq').toggleClass('chide');
        $('.appbl-print input').toggleClass('chide'); */
        $('.printTherapy').toggleClass('therapycenter');
        $('.therapy-prices .tab-print .service2-print').toggleClass('chide');
        $('.h3therapy').toggleClass('chide');
        $('.therapy-prices .tab-print').toggleClass('chide');
        $('.terapija-head').toggleClass('chide');
        
        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      
      $(".th-print").click(function(e) {
        e.preventDefault();
        $('.th-print').toggleClass('activeprint');
        $('.master-print .header-print').toggleClass('chide'); 
        $('.appblood-print').toggleClass('chide');
        $('.underline').toggleClass('chide');

        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      

      $(".removecomment-print").click(function(e) {
        e.preventDefault();
        $('.removecomment-print').toggleClass('activeprint');
        $('.headcomment').toggleClass('chide'); 
        $('.printCommentAccount-edit-print').toggleClass('chide');
        $('.underline').toggleClass('chide');

        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });

      $(".drremovecomment-print").click(function(e) {
        e.preventDefault();
        $('.drremovecomment-print').toggleClass('activeprint');
        $('.drheadcomment').toggleClass('chide'); 
        $('.drprint').toggleClass('chide');
        $('.underline').toggleClass('chide');

        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });

      $(".removeprice-bloodanalysis").click(function(e) {
        e.preventDefault();
        $('.removeprice-bloodanalysis').toggleClass('activeprint');
        $('.price-print').toggleClass('chide'); 


        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });

      $(".remove-ukupno").click(function(e) {
        e.preventDefault();
        $('.remove-ukupno').toggleClass('activeprint');
        $('.ukupnoprint').toggleClass('chide'); 


        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      $(".remove-date-print").click(function(e) {
        e.preventDefault();
        $('.remove-date-print').toggleClass('activeprint');
        $('.date-print').toggleClass('chide'); 


        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      $(".remove-id-print").click(function(e) {
        e.preventDefault();
        $('.remove-id-print').toggleClass('activeprint');
        $('.dbid').toggleClass('chide'); 


        var clicks5 = $(this).data('clicks');
        if (clicks5) {
            
            /* $('.print-body input').show();
            $('.service2-print span').show();
            $('.hideallq').hide();
            $('.appbl-print input').hide();
            $('.ukupno2print').hide();
            $('.ukupnoprint input').hide();  */
        } else {

        }
        $(this).data("clicks", !clicks5);
      });
      
    
/*       $(document).ready(function(){
        $(".search-output").click(function(){
          $(this).animate({height:40},200);
        },function(){
          $(this).animate({height:10},200);
        });
      }); */

      /* var curHeight = $('.search-output').height();
      $('.search-output').css('height', 'auto');
      var autoHeight = $('.search-output').height();
      $('.search-output').height(curHeight).animate({height: autoHeight}, 1000);

      var el = $('.search-output'),
        curHeight = el.height(),
        autoHeight = el.css('height', 'auto').height();
        el.height(curHeight).animate({height: autoHeight}, 1000); */
/* 
        let box = document.getElementById('searchOutput'),
    btn = document.getElementsByClassName('.chosenPatient');

btn.addEventListener('click', function () {
  
  if (box.classList.contains('hidden')) {
    box.classList.remove('hidden');
    setTimeout(function () {
      box.classList.remove('visuallyhidden');
    }, 20);
  } else {
    box.classList.add('visuallyhidden');    
    box.addEventListener('transitionend', function(e) {
      box.classList.add('hidden');
    }, {
      capture: false,
      once: true,
      passive: false
    });
  }
  
}, false); */
/* $( ".search-output" ).animate({
    width: [ "toggle", "swing" ],
    height: [ "toggle", "swing" ],
    opacity: "toggle"
  }, 500, "linear", function() {
  }); */
  /* $( ".search-input" ).click(function() {
    $( ".search-output" ).animate({
      height: "50"
    }, {
      duration: 500,
      specialEasing: {
        height: "100"
      },
      
    });
  }); */
/* $('.search-output').slideToggle(function (e) {
    e.preventDefault();
    content.innerHTML = htmlcontent;
    content.classList.add("animate");
    setTimeout(function() {
      content.classList.remove("animate");
    }, 500);
  }); */


  $(document).on("click", "#startingTherapy button:first-child", function (e) {
    e.preventDefault();
    $(".potrosni-materijal .single:nth-child(1)").hide();
    $(".fav-medicines .single:nth-child(-n+2)").hide();
    $(".fav-medicines .single:nth-child(5)").hide();
    $(".fav-medicines .single:nth-child(3)").show();
    $(".fav-medicines .single:nth-child(4)").show();
    $(".potrosni-materijal .single:nth-child(2)").show();
  });
  $(document).on("click", "#startingTherapy button:nth-child(2)", function (e) {
    e.preventDefault();
    $(".potrosni-materijal .single:nth-child(2)").hide();
    $(".potrosni-materijal .single:nth-child(1)").show();
    
    $(".fav-medicines .single:nth-child(-n+4)").hide();
    $(".fav-medicines .single:nth-child(5)").show();

  });
  $(document).on("click", "#startingTherapy1 button:first-child", function (e) {
    e.preventDefault();
    $(".potrosni-materijal .single:nth-child(1)").hide();
    $(".fav-medicines .single:nth-child(-n+2)").hide();
    $(".fav-medicines .single:nth-child(5)").hide();
    $(".fav-medicines .single:nth-child(3)").show();
    $(".fav-medicines .single:nth-child(4)").show();
    $(".potrosni-materijal .single:nth-child(2)").show();
  });
  $(document).on("click", "#startingTherapy1 button:nth-child(2)", function (e) {
    e.preventDefault();
    $(".potrosni-materijal .single:nth-child(2)").hide();
    $(".potrosni-materijal .single:nth-child(1)").show();
    
    $(".fav-medicines .single:nth-child(-n+4)").hide();
    $(".fav-medicines .single:nth-child(5)").show();

  });


$(document).ready(function() {
    $(".info-msg").delay(1500).fadeIn(500);
    /* setTimeout(function() {
      $('.info-msg').fadeOut(300);
    }, 10000); */
    
    
});
$(".exit-fckin").click(function(e) {
  e.preventDefault();
  $(".info-msg").fadeOut(300);
});



$(document).ready(function() {
  $(".info-msg").delay(1500).fadeIn(500);
  setTimeout(function() {
      $('.info-msg').fadeOut(300);
  }, 10000);
  $(".exit-alert-box").click(function(e) {
      e.preventDefault();
      $(".info-msg").hide();
    });
});





/* $(document).ready(function () {

  var start = new Date();
  var end = new Date();
  var time = new Date().getTime();
  
     //Set the start hours and ending hours
  if (time > start.setHours(8,30) && time < end.setHours(17,40)) {
      $(".7do10-msg").show().delay(1500).fadeIn(500);
  }
  else {
      $('.7do10-msg').fadeOut(300);
      }
  }); */






/* 

  function isOpen() {
    var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun,", "Jul",
        "Aug", "Sep", "Oct", "Nov", "Dec"];

    var date = new Date();

    var current_week_day = days[date.getDay()];
    
    var hour = date.getHours();
    var minute = date.getMinutes();
    var day = date.getDate();
    var month = months[date.getMonth()];
    var year = date.getFullYear();

    var opening_hours = Array();
    opening_hours["Sun"] = [0000, 2157];
    opening_hours["Mon"] = [0900, 2100];
    opening_hours["Tue"] = [0900, 2100];
    opening_hours["Wed"] = [0900, 2100];
    opening_hours["Thu"] = [0900, 2100];
    opening_hours["Fri"] = [0900, 2100];
    opening_hours["Sat"] = [0900, 1851];
    
    var days_closed = [
        {"day": 25, "month": "Dec"},
        {"day": 26, "month": "Dec"}
    ];

    if(current_week_day == "Sun") {
        return false;
    }

    if(current_week_day == "Sat") {
        return false;
    }

    var should_be_closed = days_closed.every(function(value) {

        if(day == value["day"] && month == value["month"]) {
            return false;
        }
        
        return true;
    });
    
    if(!should_be_closed) {
        return false;
    }

    var today_hours = opening_hours[current_week_day];
    var current_time = hour.toString() + minute.toString();

    if(today_hours[0] <= current_time && today_hours[1] > current_time) {
        return true;
    }
}

jQuery(document).ready(function () {            
    jQuery('.need-help-header').click(function () {
        jQuery('.need-help-content').slideToggle();
    });

    if(isOpen()) {
        jQuery(".contact-popup").show();
    }
}); */

/*   $(document).ready(function () {
    var nowPlus30Seconds = moment().add('10', 'seconds').format('YYYY/MM/DD HH:mm:ss');
    
    $('.count1').countdown(nowPlus30Seconds)
    .on('update.countdown', function (event) { $(this).html(event.strftime('%Y : %D : %H : %M : %S')); })
    .on('finish.countdown', function () { $('.7do10-msg').fadeOut(300); });
    }); */