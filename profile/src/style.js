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

            const parentContainer = div.parentElement;
            const parentHeight = parentContainer.clientHeight;
            const elementHeight = div.clientHeight;
          
            // Expand the element
            // element.style.display = 'block';
          
            // Calculate the scroll position to center the element within the parent container
            const scrollPosition = div.offsetTop - (parentHeight / 2) + (elementHeight / 2);
          
            // Scroll to the calculated position within the parent container
            parentContainer.scrollTo({
              top: scrollPosition,
              behavior: 'smooth'
            });

            div.style.display= 'block';
            div.style.height= 'auto';
        }else{
            div.style.height= '0';
            div.style.display= 'none';

        }
    }


}
var stylePage = new styleThisPage();

