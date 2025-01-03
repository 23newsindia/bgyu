function handleDownloadClick(event, button) {
    const container = button.closest('.sfd-download-button');
    container.classList.add('clicked');
    
    // Create fireworks
    createFireworks(event.clientX, event.clientY);
    
    // Remove clicked class after animation
    setTimeout(() => {
        container.classList.remove('clicked');
    }, 3000);
}

function createFireworks(x, y) {
    const firework = document.createElement('div');
    firework.className = 'firework';
    document.body.appendChild(firework);

    // Create multiple particles
    const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
    const particleCount = 30;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Random angle and distance
        const angle = (Math.PI * 2 * i) / particleCount;
        const velocity = 2 + Math.random() * 3;
        const dx = Math.cos(angle) * 100 * velocity;
        const dy = Math.sin(angle) * 100 * velocity;

        particle.style.backgroundColor = colors[i % colors.length];
        particle.style.setProperty('--x', `${x}px`);
        particle.style.setProperty('--y', `${y}px`);
        particle.style.setProperty('--dx', `${dx}px`);
        particle.style.setProperty('--dy', `${dy}px`);

        firework.appendChild(particle);
    }

    // Remove firework after animation
    setTimeout(() => {
        document.body.removeChild(firework);
    }, 1000);
}