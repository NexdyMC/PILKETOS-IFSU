tailwind.config = {
  theme: {
    extend: {
      colors: {
        brand: {
          blue: '#0047AB',
          darkblue: '#0F172A',
          yellow: '#FACC15',
          yellowhover: '#EAB308'
        },
        navy: {
          950: '#0B1424',
          900: '#0F172A',
          800: '#152238',
          700: '#1E293B',
        },
        primary: {
          50:  '#EEF3FF',
          100: '#DCE7FF',
          500: '#2563EB',
          600: '#1D4ED8',
          700: '#1E3A8A',
        },
        accent: {
          300: '#FDE68A',
          400: '#FACC15',
          500: '#EAB308',
        },
      },
      fontFamily: {
        display: ['Poppins', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
      },
      // ===== TAMBAHAN =====
      keyframes: {
        'fade-up': {
          '0%':   { opacity: '0', transform: 'translateY(16px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        'float': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%':      { transform: 'translateY(-12px)' },
        },
      },
      animation: {
        'fade-up': 'fade-up 0.6s ease-out both',
        'float':   'float 6s ease-in-out infinite',
      },
      boxShadow: {
        'glow': '0 10px 40px -10px rgba(250, 204, 21, 0.45)',
      },
    }
  }
}