        let z=1, drag=false, x=0, y=0, tx=0, ty=0;

        function openModal(fullImageUrl = null) {
            document.getElementById('modal').style.display = "block";
            const modalImg = document.getElementById('modalImg');
            
            // Sử dụng URL được truyền vào hoặc src của ảnh hiện tại
            if (fullImageUrl) {
                modalImg.src = fullImageUrl;
            } else {
                modalImg.src = document.querySelector('.project-image').src;
            }
            
            reset();
        }

        function closeModal() { document.getElementById('modal').style.display = "none"; }
        
        function zoom(d) { event?.stopPropagation(); z = Math.max(0.5, Math.min(5, z + d)); update(); }
        
        function reset() { event?.stopPropagation(); z=1; tx=0; ty=0; update(); }
        
        function update() {
            const img = document.getElementById('modalImg');
            img.style.transform = `translate(${tx}px, ${ty}px) scale(${z})`;
            img.style.cursor = z > 1 ? (drag ? 'grabbing' : 'grab') : 'default';
        }

        // Events
        document.getElementById('modalImg').addEventListener('wheel', e => { e.preventDefault(); zoom(e.deltaY < 0 ? 0.1 : -0.1); });
        
        document.getElementById('modalImg').addEventListener('mousedown', e => {
            if (z > 1) { drag = true; x = e.clientX - tx; y = e.clientY - ty; e.preventDefault(); }
        });
        
        document.addEventListener('mousemove', e => { if (drag) { tx = e.clientX - x; ty = e.clientY - y; update(); } });
        document.addEventListener('mouseup', () => drag = false);
        document.addEventListener('keydown', e => e.key === 'Escape' && closeModal());
        document.getElementById('modalImg').addEventListener('click', e => e.stopPropagation());
        document.addEventListener('DOMContentLoaded', () => document.querySelector('.controls')?.addEventListener('click', e => e.stopPropagation()));
    