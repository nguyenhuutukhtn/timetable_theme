(function($) {
    $(document).ready(function() {
        const canvas = document.getElementById('lucky-wheel');
        const ctx = canvas.getContext('2d');
        const wheelCenter = document.getElementById('wheel-center');
        const spinText = document.getElementById('spin-text');
        const resultDiv = document.getElementById('result');
        const facebookLink = document.getElementById('fb-link');
        const form = $('#lucky-wheel-form');


        const wheelConfig = {
            segments: lucky_wheel_config.segments,
            centerX: canvas.width / 2,
            centerY: canvas.height / 2,
            radius: 140,
            textDistance: 60
        };

        
        let isSpinning = false;
        let startAngle = 0;
        let isFormSubmitted = false;

        // Draw the wheel initially
        drawWheel();

        // Handle form submission
        form.on('submit', function(e) {
            e.preventDefault();
            if (validateForm()) {
                $.ajax({
                    url: lucky_wheel_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'lucky_wheel_register',
                        formData: form.serialize()
                    },
                    success: function(response) {
                        if (response.success) {
                            isFormSubmitted = true;
                            enableWheel();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ": " + error);
                    }
                });
            }
        });

        function validateForm() {
            let isValid = true;
            form.find('input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            return isValid;
        }

        function enableWheel() {
            wheelCenter.style.pointerEvents = 'auto';
            spinText.textContent = 'SPIN!';
        }

        function drawWheel() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(wheelConfig.centerX, wheelConfig.centerY);
            ctx.rotate(startAngle);

            const segmentAngle = (Math.PI * 2) / wheelConfig.segments.length;

            for (let i = 0; i < wheelConfig.segments.length; i++) {
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.arc(0, 0, wheelConfig.radius, i * segmentAngle, (i + 1) * segmentAngle);
                ctx.lineTo(0, 0);
                ctx.fillStyle = wheelConfig.segments[i].color;
                ctx.fill();
                ctx.stroke();

                // Draw text
                ctx.save();
                ctx.rotate(segmentAngle / 2 + i * segmentAngle);
                ctx.textAlign = 'right';
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 12px Arial';
                ctx.fillText(wheelConfig.segments[i].label, wheelConfig.radius - 10, 5);
                ctx.restore();
            }

            ctx.restore();
        }

        function easeOut(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function createWheel() {
    const wheel = document.getElementById('lucky-wheel');
    const segmentAngle = 360 / wheelConfig.segments.length;

    wheelConfig.segments.forEach((segment, index) => {
        const startAngle = index * segmentAngle;
        const endAngle = (index + 1) * segmentAngle;
        
        const segmentPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
        const x1 = 50 + 50 * Math.cos(Math.PI * startAngle / 180);
        const y1 = 50 + 50 * Math.sin(Math.PI * startAngle / 180);
        const x2 = 50 + 50 * Math.cos(Math.PI * endAngle / 180);
        const y2 = 50 + 50 * Math.sin(Math.PI * endAngle / 180);
        
        segmentPath.setAttribute("d", `M50,50 L${x1},${y1} A50,50 0 0,1 ${x2},${y2} Z`);
        segmentPath.setAttribute("class", "wheel-segment");
        wheel.appendChild(segmentPath);

        const textX = 50 + 35 * Math.cos(Math.PI * (startAngle + segmentAngle / 2) / 180);
        const textY = 50 + 35 * Math.sin(Math.PI * (startAngle + segmentAngle / 2) / 180);
        
        const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
        text.setAttribute("x", textX);
        text.setAttribute("y", textY);
        text.setAttribute("class", "prize-text");
        text.setAttribute("transform", `rotate(${startAngle + segmentAngle / 2}, ${textX}, ${textY})`);
        text.textContent = segment.prize;
        wheel.appendChild(text);
    });
}

        createWheel();

        function spinWheel() {
            if (isSpinning || !isFormSubmitted) return;
            isSpinning = true;
            facebookLink.classList.add('hidden');
            wheelCenter.classList.add('spinning');
            spinText.style.opacity = '0';
            resultDiv.textContent = '';

            const totalRotation = 5 * Math.PI * 2 + Math.random() * Math.PI * 2;
            const duration = 5000;
            const start = performance.now();

            function animate(time) {
                const elapsed = time - start;
                const progress = Math.min(elapsed / duration, 1);
                const easeProgress = easeOut(progress);
            
                startAngle = easeProgress * totalRotation;
                drawWheel();
            
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    isSpinning = false;
                    wheelCenter.classList.remove('spinning');
                    
                    // Calculate the winning segment
                    let finalAngle = startAngle % (Math.PI * 2);
                    if (finalAngle < 0) finalAngle += Math.PI * 2;
                    const segmentAngle = (Math.PI * 2) / wheelConfig.segments.length;
                    
                    // Adjust the final angle to account for the pointer's starting position
                    const pointerOffset = Math.PI / 2; // Assuming the pointer starts at the top (90 degrees)
                    finalAngle = (finalAngle + pointerOffset) % (Math.PI * 2);
                    
                    // Calculate the winning segment index
                    const winningSegmentIndex = Math.floor((Math.PI * 2 - finalAngle) / segmentAngle) % wheelConfig.segments.length;
                    
                    const winningSegment = wheelConfig.segments[winningSegmentIndex];
                    
                    resultDiv.textContent = "Phần thưởng của bạn: " + winningSegment.label;
                    spinText.style.opacity = '1';
                    spinText.textContent = 'SPIN!';
                    facebookLink.classList.remove('hidden');
                }
            }

            requestAnimationFrame(animate);
        }

        document.querySelector('.wheel-center').addEventListener('click', spinWheel);
        wheelCenter.style.pointerEvents = 'none'; // Disable spinning until form is submitted
    });
})(jQuery);