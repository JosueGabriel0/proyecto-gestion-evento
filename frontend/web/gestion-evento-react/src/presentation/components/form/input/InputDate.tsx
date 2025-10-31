import React, { useRef } from 'react';
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCalendarAlt } from '@fortawesome/free-solid-svg-icons';

interface InputDateProps {
  className?: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  label?: string;
}

const InputDate: React.FC<InputDateProps> = ({ className, value, onChange, label }) => {
  const inputRef = useRef<HTMLInputElement>(null);

  const handleIconClick = () => {
    // ✅ Al hacer clic en el ícono, abrimos el selector nativo
    inputRef.current?.showPicker?.(); // Chrome, Edge modernos
    if (inputRef.current) inputRef.current.focus();
  };

  return (
    <StyledWrapper>
      <div className={`${className} brutalist-container`}>
        <input
          ref={inputRef}
          className="brutalist-input"
          type="date"
          value={value}
          onChange={onChange}
        />
        {label && <label className="brutalist-label">{label}</label>}

        {/* 📅 Ícono de calendario */}
        <FontAwesomeIcon
          icon={faCalendarAlt}
          className="calendar-icon"
          onClick={handleIconClick}
        />
      </div>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div`
  .brutalist-container {
    position: relative;
    width: 260px;
    font-family: monospace;
  }

  .brutalist-input {
    width: 100%;
    padding: 15px 40px 15px 15px; /* deja espacio para el ícono */
    font-size: 18px;
    font-weight: bold;
    color: #000;
    background-color: #fff;
    border: 4px solid #000;
    border-radius: 0;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 5px 5px 0 #000, 10px 10px 0 #4a90e2;
    cursor: pointer;
  }

  .brutalist-input:focus {
    border-color: #4a90e2;
  }

  .brutalist-label {
    position: absolute;
    left: -3px;
    top: -35px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #000;
    padding: 5px 10px;
    transform: rotate(-1deg);
    z-index: 1;
  }

  /* 📅 Ícono de calendario */
  .calendar-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #000;
    cursor: pointer;
    transition: color 0.2s ease;
  }

  .calendar-icon:hover {
    color: #4a90e2;
  }

  /* 🌓 Adaptación al modo oscuro */
  @media (prefers-color-scheme: dark) {
    .brutalist-input {
      background-color: #1e1e1e;
      color: #fff;
      border-color: #fff;
    }

    .calendar-icon {
      color: #fff;
    }

    .calendar-icon:hover {
      color: #4a90e2;
    }
  }
`;

export default InputDate;