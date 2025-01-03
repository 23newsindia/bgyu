function animateRobot(container) {
    const robot = container.querySelector('.robot');
    const button = container.querySelector('.button');
    
    // Sequence of animations
    setTimeout(() => {
        // Door opens and robot appears
        button.classList.add('door-open');
        robot.style.opacity = '1';
        robot.style.visibility = 'visible';
        
        // Robot jumps and waves
        setTimeout(() => {
            robot.classList.add('waving');
            
            // Show message
            container.querySelector('.robot-message').style.opacity = '1';
            container.querySelector('.robot-message').style.visibility = 'visible';
            
            // Reset animations after 3 seconds
            setTimeout(() => {
                button.classList.remove('door-open');
                robot.classList.remove('waving');
                robot.style.opacity = '0';
                robot.style.visibility = 'hidden';
                container.querySelector('.robot-message').style.opacity = '0';
                container.querySelector('.robot-message').style.visibility = 'hidden';
            }, 3000);
        }, 500);
    }, 100);
}