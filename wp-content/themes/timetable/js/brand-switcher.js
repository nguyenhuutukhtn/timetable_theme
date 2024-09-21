document.addEventListener('DOMContentLoaded', () => {
    const termDetails = document.querySelector('.term-details');
    const termsList = document.querySelector('.terms-list');

    // Function to update term details
    function updateTermDetails(term) {
        const title = term.querySelector('h3').textContent;
        const period = term.querySelector('p:first-of-type').textContent;
        const subject = term.querySelector('p:last-of-type').textContent;

        termDetails.innerHTML = `
            <h3>${title}</h3>
            <p>${period}</p>
            <h4>${subject}</h4>
            <ul>
                <li>Lesson 1: ${subject} 1</li>
                <li>Lesson 2: ${subject} 2</li>
                <li>Lesson 3: ${subject} 3</li>
                <li>Lesson 4: ${subject} 4</li>
                <li>Lesson 5: Further ${subject} 1</li>
                <li>Lesson 6: Further ${subject} 2</li>
                <li>Lesson 7: Further ${subject} 3</li>
                <li>Lesson 8: Advanced ${subject}</li>
                <li>Lesson 9: End of Term Topic Test</li>
            </ul>
        `;
    }

    // Add click event listeners to terms
    termsList.addEventListener('click', (event) => {
        const clickedTerm = event.target.closest('.term');
        if (clickedTerm) {
            // Remove active class from all terms
            termsList.querySelectorAll('.term').forEach(term => term.classList.remove('active'));
            // Add active class to clicked term
            clickedTerm.classList.add('active');
            // Update term details
            updateTermDetails(clickedTerm);
        }
    });

    // Initialize with the first term
    const firstTerm = termsList.querySelector('.term');
    if (firstTerm) {
        firstTerm.classList.add('active');
        updateTermDetails(firstTerm);
    }

    // Brand switcher functionality
    document.querySelectorAll('.brand-btn').forEach(button => {
        button.addEventListener('click', () => {
            // Remove the active class from all buttons
            document.querySelectorAll('.brand-btn').forEach(btn => btn.classList.remove('active'));
            // Add the active class to the clicked button
            button.classList.add('active');

            // Get the selected brand
            const brand = button.getAttribute('data-brand');

            // Hide all terms
            document.querySelectorAll('.term').forEach(term => {
                term.classList.add('hidden');
            });

            // Show terms for the selected brand
            document.querySelectorAll(`.term[data-brand="${brand}"]`).forEach(term => {
                term.classList.remove('hidden');
            });

            // Update term details with the first visible term
            const firstVisibleTerm = termsList.querySelector(`.term[data-brand="${brand}"]:not(.hidden)`);
            if (firstVisibleTerm) {
                firstVisibleTerm.classList.add('active');
                updateTermDetails(firstVisibleTerm);
            }
        });
    });
});
