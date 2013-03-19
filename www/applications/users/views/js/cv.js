function CvExperience($scope) {
    console.log("CvExperience");
    $scope.experiences = [
        <?php
        for ($experience = 0; $experience < count($experiences); $experience++) {
        ?>
            {
                idexperience: "<?php print recoverPOST("idexperience$experience", $experiences[$experience]["ID_Experience"]); ?>",
                company: "<?php print recoverPOST("company$experience", $experiences[$experience]["Company"]); ?>",
                title: "<?php print recoverPOST("title$experience", $experiences[$experience]["Title"]); ?>",
                location: "<?php print recoverPOST("location$experience", $experiences[$experience]["Location"]); ?>",
                periodfrom: "<?php print recoverPOST("periodfrom$experience", $experiences[$experience]["Period_From"]); ?>",
                periodto: "<?php print recoverPOST("periodto$experience", $experiences[$experience]["Period_To"]); ?>",
                description: "<?php print removeBreaklines(recoverPOST("description$experience", $experiences[$experience]["Description"]), "\\n"); ?>",
                editor: null
            } <?php print $experience < (count($experiences) - 1) ? ',' : '';
        }
        ?>
    ];

    $scope.addExperience = function () {
        console.log("addExperience");
        var index = $scope.experiences.length;
        
        $scope.experiences.push({
            id: "", company: "", title: "", location: "", periodfrom: "", periodto: "", description: "", editor: null
        });
        
        window.setTimeout(function () {
            $('html, body').animate({
                scrollTop: $("#company" + ($scope.experiences.length - 1)).parent().parent().offset().top - 10
            }, 1000, function () {
                $("#experience" + ($scope.experiences.length - 1)).focus();
            });
        }, 0);
    };

    $scope.removeExperience = function (index) {
        console.log("removeExperience");
        if (index > 0) {
            if (confirm("<?php print __("Do you want to remove this experience?"); ?>")) {
                this.experiences.splice(index, 1);
            }
        }
    };
}

function CvEducation($scope) {
    console.log("CvEducation");
     $scope.education = [
        <?php
        for ($school = 0; $school < count($education); $school++) {
        ?>
            {
                idschool: "<?php print recoverPOST("idschool$school", $education[$school]["ID_School"]); ?>",
                school: "<?php print recoverPOST("school$school", $education[$school]["School"]); ?>",
                degree: "<?php print recoverPOST("degree$school", $education[$school]["Degree"]); ?>",
                periodfrom: "<?php print recoverPOST("periodfrom$school", $education[$school]["Period_From"]); ?>",
                periodto: "<?php print recoverPOST("periodto$school", $education[$school]["Period_To"]); ?>",
                description: "<?php print removeBreaklines(recoverPOST("description$school", $education[$school]["Description"]), "\\n"); ?>",
                editor: null
            } <?php print $school < (count($education) - 1) ? ',' : '';
        }
        ?>
    ];

    $scope.addSchool = function () {
        console.log("addSchool");
        var index = $scope.education.length;
        
        $scope.education.push({
            id: "", school: "", degree: "", periodfrom: "", periodto: "", description: "", editor: null
        });
        
        window.setTimeout(function () {
            $('html, body').animate({
                scrollTop: $("#school" + ($scope.education.length - 1)).parent().parent().offset().top - 10
            }, 1000, function () {
                $("#syntax" + ($scope.education.length - 1)).focus();
            });
        }, 0);
    };

    $scope.removeSchool = function (index) {
        console.log("removeSchool"); 
        if (index > 0) {
            if (confirm("<?php print __("Do you want to remove this institute?"); ?>")) {
                this.education.splice(index, 1);
            }
        }
    };
    //Configurar addschool y removeschool para que se utilicen para en school y experience

}