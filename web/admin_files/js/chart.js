let chartBox = document.getElementById("browser-usage");
let labels = $(chartBox).data('labels');
let visitors = $(chartBox).data('visitors');
let ctx = chartBox.getContext('2d');
let graphColors = [];
let graphOutlines = [];
let hoverColor = [];
let internalDataLength = labels.length;
let i = 0;

while (i <= internalDataLength) {
    let randomR = Math.floor((Math.random() * 130) + 100);
    let randomG = Math.floor((Math.random() * 130) + 100);
    let randomB = Math.floor((Math.random() * 130) + 100);

    let graphBackground = "rgb("
        + randomR + ", "
        + randomG + ", "
        + randomB + ")";
    graphColors.push(graphBackground);

    let graphOutline = "rgb("
        + (randomR - 80) + ", "
        + (randomG - 80) + ", "
        + (randomB - 80) + ")";
    graphOutlines.push(graphOutline);

    let hoverColors = "rgb("
        + (randomR + 25) + ", "
        + (randomG + 25) + ", "
        + (randomB + 25) + ")";
    hoverColor.push(hoverColors);

    i++;
}

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: '# of Votes',
            data: visitors,
            backgroundColor: graphColors,
            borderColor: graphOutlines,
            borderWidth: 1
        }]
    },
    options: {}
});