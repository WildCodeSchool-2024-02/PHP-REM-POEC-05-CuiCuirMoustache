$(document).ready(function () {
  // Fonction pour gérer les labels en fonction de l'état des champs
  $(".form").find("input, textarea").on("keyup blur focus", function (e) {
    var $this = $(this),
        $label = $this.siblings("label");

    if (e.type === "keyup" || e.type === "focus") {
      if ($this.val() !== "") {
        $label.addClass("hide");
      } else {
        $label.removeClass("hide");
      }
    } else if (e.type === "blur") {
      if ($this.val() === "") {
        $label.removeClass("hide");
      }
    }
  });

  // Fonction pour réinitialiser les champs du formulaire et les erreurs
  function resetForm(formId) {
    $(formId).find("input, textarea").val('');
    $(formId).find("label").removeClass("hide");
    $(formId).find(".errors").hide(); // Masquer les erreurs
  }

  // Gestion des onglets
  $(".tab a").on("click", function (e) {
    e.preventDefault();

    var target = $(this).attr("href");

    // Modifier l'URL sans recharger la page
    history.pushState(null, null, target);

    // Ajouter la classe active à l'onglet sélectionné et la retirer des autres
    $(this).parent().addClass("active");
    $(this).parent().siblings().removeClass("active");

    // Masquer les autres contenus d'onglet
    $(".tab-content > div").not(target).hide();

    // Réinitialiser les champs du formulaire et les erreurs dans l'onglet non visible
    $(target).find("input, textarea").val('');
    $(target).find("label").removeClass("hide");
    $(target).find(".errors").hide(); // Masquer les erreurs

    // Afficher le contenu de l'onglet sélectionné avec un effet de fondu
    $(target).fadeIn(600);
  });

  // Gérer le chargement initial
  var initialTab = window.location.hash || ".tab-content > div:first";
  $(initialTab).show();
  $(".tab a[href='" + initialTab + "']").parent().addClass("active");
});
