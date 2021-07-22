document.addEventListener( 'DOMContentLoaded', function () {
    var splide = new Splide( '.splide',{
        type      : 'loop',
        perPage   : 3,
        throttle  : 100,
        height    : '32em',
        autoplay  : true,
        cover     : false,
        breakpoins: {
            640: {
                height: '6rem',
            }
        }
    });
    splide.mount();



    // category sorting
    const selectedCat = document.querySelectorAll('.cat-item');
    selectedCat.forEach(catItem => {
        catItem.addEventListener('click', e => {
            const boxs = document.querySelectorAll('.all');
            const category = catItem.id;
            
            console.log(category);


            const allCat = document.querySelectorAll('.cat-item');// catItem.shibling.classList.remove('active');;
            allCat.forEach(cat =>{
                console.log(cat);
                if(cat.classList.contains('active')){
                    cat.classList.remove('active');
                }
            })
            
            catItem.classList.add('active');
            splide.destroy();
            // console.log(category);

            boxs.forEach(box => {
                box.style.display = 'none'; 
                box.classList.remove('splide__slide');
                
                if(box.classList.contains(category)){
                    box.style.display = "block";
                    box.classList.add('splide__slide');
                }
            })
            splide.mount();
        })
    })


} );