<?php
    //pull hotspotID from the GET query in the URL
    $item = $_GET['hotspotId'];
    //pull all other related data from the database
    $hotspotId = $database->prepare('SELECT hotspotName, address, suburb, latitude, longitude FROM items WHERE hotspotId = :hotspotId');
    $hotspotId->bindValue(':hotspotId', $item);
    $hotspotId->execute();
    //set variables for each item of info for the hotspot
    foreach($hotspotId as $hotspot){
        $name = $hotspot['hotspotName'];
        $address = $hotspot['address'];
        $suburb = $hotspot['suburb'];
        $lat = $hotspot['latitude'];
        $long = $hotspot['longitude'];
    }
?>
<!-- set javascript values for the php variables-->
<script language="javascript" type="text/javascript">
    var hotspotName = "<?php echo $name; ?>";
    var address = "<?php echo $address; ?>";
    var suburb = "<?php echo $suburb; ?>";
    var latitude = parseFloat("<?php echo $lat; ?>");
    var longitude = parseFloat("<?php echo $long; ?>");
</script>