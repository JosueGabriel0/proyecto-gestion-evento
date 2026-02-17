import { faFile } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState, useEffect, useRef } from "react";

interface InputFileProps {
  file?: File | null;
  onChange?: (file: File | null) => void;
  initialUrl?: string;
  label?: string;
}

const InputFile: React.FC<InputFileProps> = ({ file, onChange, initialUrl, label }) => {
  const [previewUrl, setPreviewUrl] = useState<string | null>(initialUrl || null);
  const inputRef = useRef<HTMLInputElement | null>(null);

  useEffect(() => {
    if (file) {
      const objectUrl = URL.createObjectURL(file);
      setPreviewUrl(objectUrl);
      return () => URL.revokeObjectURL(objectUrl);
    }
  }, [file]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const selected = e.target.files?.[0] || null;
    onChange?.(selected);
  };

  const handleRemoveImage = () => {
    setPreviewUrl(null);
    onChange?.(null);
    if (inputRef.current) inputRef.current.value = "";
  };

  return (
    <div className="flex flex-col items-center justify-between p-3 gap-3 h-[300px] w-[300px] rounded-lg shadow-lg bg-gray-50 dark:bg-gray-900 transition-all duration-300">
      <div className="flex flex-col items-center justify-center w-full flex-1 border-2 border-dashed border-blue-500 rounded-lg relative overflow-hidden">
        {previewUrl ? (
          <div className="relative w-full h-full">
            <img
              src={previewUrl}
              alt="Vista previa"
              className="w-full h-full object-cover rounded-lg"
            />
            <button
              type="button"
              onClick={handleRemoveImage}
              title="Eliminar imagen"
              className="absolute bottom-3 right-3 bg-red-600 hover:bg-red-700 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl transition-transform hover:scale-110"
            >
              🗑️
            </button>
          </div>
        ) : (
          <>
            {/* 📷 Ícono central */}
            <svg
              viewBox="0 0 24 24"
              fill="none"
              className="w-12 h-12 text-gray-800 dark:text-gray-100 transition-colors duration-300"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5"
                stroke="currentColor"
                strokeWidth="1.5"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
            <p className="mt-2 text-gray-800 dark:text-gray-100 font-medium text-center">
              {label || "Selecciona una imagen para subir"}
            </p>
          </>
        )}
      </div>

      {/* 📂 Footer con botón y texto */}
      <label
        htmlFor="file"
        className="flex w-full h-12 bg-blue-50 dark:bg-gray-800 rounded-lg px-3 cursor-pointer border border-gray-200 dark:border-gray-700 transition-colors duration-300 hover:bg-blue-100 dark:hover:bg-gray-700"
      >
        <div className="flex items-center gap-2 w-full">
          <div className="flex mt-2 gap-1">
            <div>
              <FontAwesomeIcon
                icon={faFile}
                className="text-gray-800 dark:text-gray-100 transition-colors duration-300"
              />
            </div>
            <p
              title={file ? file.name : "Ningún archivo seleccionado"}
              className="flex-1 text-center text-gray-800 dark:text-gray-100 truncate overflow-hidden whitespace-nowrap max-w-[200px]"
            >
              {file ? file.name : "Ningún archivo seleccionado"}
            </p>
          </div>
        </div>
      </label>

      <input
        ref={inputRef}
        type="file"
        accept="image/*"
        onChange={handleFileChange}
        className="hidden"
      />
    </div>
  );
};

export default InputFile;