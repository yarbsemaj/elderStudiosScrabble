//Toaster options
 toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "500",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

//Leaderboard
var app = angular.module('hudScrabble', []);

app.controller('leaderBoard', function ($scope, $http) {
    $http.get("php/leaderBoard.php")
        .then(function (response) {
            $scope.scores = response.data.members;
        });
});

//Member cRud
app.controller('memberInfo', function ($scope, $http) {
    $scope.showMemberInfo = function ($event) {
        $http({
            url: '/php/playerInfo.php',
            method: "POST",
            data: { 'memberID': $scope.memberID },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        }).then(function (response) {
            if (response.data.success) {
                console.log(response.data);
                $("#playerInfo").modal();
                $scope.loss ="0";
                $scope.avg  ="0";
                $scope.wins  ="0";
                $scope.loss = response.data.loss;
                $scope.avg = response.data.avg;
                $scope.wins = response.data.wins;
                
                $scope.joinDate = response.data.memberData.joinDate;
                $scope.name = response.data.memberData.name;
                $scope.adress1 = response.data.memberData.adress1;
                $scope.adress2 = response.data.memberData.adress2;
                $scope.postCode = response.data.memberData.postCode;
                $scope.memberID = response.data.memberData.memberID;
                $scope.mode = "edit";

                $scope.opScore = "";
                $scope.playerScore = "";
                $scope.location = "";
                $scope.opName = "";
                $scope.time = 0;
                $scope.opScore = response.data.bestGame.opScore;
                $scope.playerScore = response.data.bestGame.playerScore;
                $scope.location = response.data.bestGame.location;
                $scope.opName = response.data.bestGame.opName;
                $scope.time = response.data.bestGame.time;
            } else {
                 toastr["error"]("The Member ID your looking for is invalid");
            }


        });
    }
//Member CrUd
 $scope.saveMember = function ($event) {
        $http({
            url: '/php/addEdit.php',
            method: "POST",
            data: {'memberID': $scope.memberID, 'name':$scope.name ,'adress1':$scope.adress1, 'adress2': $scope.adress2, 'postCode': $scope.postCode,'mode': $scope.mode},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        }).then(function (response) {
             console.log(response.data);
            if (response.data.success) {
                console.log(response.data);
                $("#editModal").modal('toggle');
                toastr["info"]("Member ID "+response.data.memberID+" successfully "+ $scope.mode +"ed");
            } else {
                toastr["error"]("A generic error has ocured");
            }


        });
    }

    $scope.addMember = function ($event) {
        $scope.name = "";
        $scope.adress1 = "";
        $scope.adress2 = "";
        $scope.postCode = "";
        $scope.memberID = "";
        $scope.mode="add";
         $("#editModal").modal();
    }
});
//tooltip fix
app.directive('tooltip', function () {
    return {
        link: function (scope, element, attrs) {
            $(element).hover(function () {
                // on mouseenter
                $(element).tooltip('show');
            }, function () {
                // on mouseleave
                $(element).tooltip('hide');
            });
        }
    };
});
//capitalize
app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});

//smoothScroll

$(function() {
  $('a[href*="#"]:not([href="#"], .carousel-control)').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});