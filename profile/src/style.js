class styleThisPage{
    constructor(){
      var params = new URLSearchParams(window.location.search);
        this.optValue = params.get('opt');
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.dashboardDiv = document.querySelector('#dashboardDiv');
       
        // check the hash and display what to show
        if (this.optValue == '' || this.optValue === null || this.optValue === 'undefined') {
            // Stay on dashboard
            this.dashboardMenu.classList.add('active');
            this.dashboardDiv.style.display = 'block';
            this.dashboardMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }else{
            var showDiv = document.getElementById(`${this.optValue}Div`);
            var showMenu = document.getElementById(`${this.optValue}Menu`);
            showMenu.classList.add('active');
            showDiv.style.display = 'block';
            showMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    exapndAndShrink(id){
        let div = document.getElementById(`${id}`);
        let title = document.querySelector('.contentTopics .title');
        let isDisplay = div.style.display;
        if (isDisplay == 'none') {

            const windowHeight = window.innerHeight;
            const elementHeight = div.clientHeight;
            
            // Calculate the scroll position to center the element
            const scrollPosition = div.offsetTop - (windowHeight / 2) + (elementHeight / 2);
            
            // Scroll to the calculated position
            window.scrollTo({
                top: scrollPosition,
                behavior: 'smooth'
            });

            div.scrollIntoView({ behavior: 'smooth', block: 'center' });

            div.style.display= 'block';
            div.style.height= 'auto';
        }else{
            div.style.height= '0';
            div.style.display= 'none';

        }
    }


}
var stylePage = new styleThisPage();

