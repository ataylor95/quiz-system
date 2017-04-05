<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    $('#prev-quiz').on('click', function() {
        removeChart();
        $.ajax({
            url: "{{route('prevQuiz')}}",
        });
    }); 
    $('#next-quiz').on('click', function() {
        removeChart();
        $.ajax({
            url: "{{route('nextQuiz')}}",
        });
    }); 
    $('#show-results').on('click', function() {
        $.ajax({
            url: "{{route('showResults', ['session_key' => $key])}}",
            success: function(data){
                var keys = [];
                var values = [];
                //create lists of the keys and values
                jQuery.each(data, function(key, value) {
                    keys.push(key);
                    values.push(value);
				});
                createChart(keys, values);
            },
        });
    }); 
    $('#end-quiz').on('click', function() {
        removeChart();
        $.ajax({
            url: "{{route('endQuiz')}}",
        });
    }); 

    /**
     * Create the chartjs object and add it to the page
     *
     * @param [] keys - array of keys
     * @param [] values - array of the values
     */
    function createChart(keys, values){
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: keys,
                datasets: [{
                    label: 'Responses',
                    data: values,
                    borderWidth: 1,
                    backgroundColor: getColours(keys.length)
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            userCallback: function(label, index, labels) {
                                // when the floored value is the same as the value we have a whole number
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
                legend: {
                    display: false
                },
            }
        });
    }

    /**
     * Gets a random rgb colour for the chart
     * Source: https://www.sitepoint.com/generating-random-color-values/
     */
    function getRandomColour(){
        return 'rgb(' + (Math.floor(Math.random() * 256)) + 
            ',' + (Math.floor(Math.random() * 256)) + 
            ',' + (Math.floor(Math.random() * 256)) + 
            ')';
    }

    /**
     * Gets the array of colours for the chart
     *
     * @param int size - number of different results
     */
    function getColours(size){
        var colourArray = [];
        for(var i=0;i<=size;i++){
            colourArray.push(getRandomColour());
        }
        return colourArray;
    }

    function removeChart(){
        $('#results-box').empty();
        $('#results-box').append(
            '<canvas id="myChart"></canvas>'
        );
    }

</script>
