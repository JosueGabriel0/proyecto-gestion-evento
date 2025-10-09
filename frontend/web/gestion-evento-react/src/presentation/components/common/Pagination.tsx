import React from "react";

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  totalItems: number;
  perPage: number;
  onPageChange: (page: number) => void;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  totalPages,
  totalItems,
  perPage,
  onPageChange,
}) => {
  if (totalPages <= 1) return null;

  const start = (currentPage - 1) * perPage + 1;
  const end = Math.min(currentPage * perPage, totalItems);

  // 🔹 Genera las páginas visibles (ejemplo con 7 máximo)
  const getPages = () => {
    const pages: (number | string)[] = [];
    if (totalPages <= 7) {
      return Array.from({ length: totalPages }, (_, i) => i + 1);
    }
    if (currentPage <= 4) {
      return [1, 2, 3, 4, 5, "...", totalPages];
    }
    if (currentPage >= totalPages - 3) {
      return [1, "...", totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
    }
    return [1, "...", currentPage - 1, currentPage, currentPage + 1, "...", totalPages];
  };

  const pages = getPages();

  return (
    <div className="flex items-center justify-between border-t border-gray-200 dark:border-white/10 px-4 py-3 sm:px-6">
      {/* Mobile */}
      <div className="flex flex-1 justify-between sm:hidden">
        <button
          disabled={currentPage === 1}
          onClick={() => onPageChange(currentPage - 1)}
          className="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-white/5 border rounded-md disabled:opacity-50"
        >
          Anterior
        </button>
        <button
          disabled={currentPage === totalPages}
          onClick={() => onPageChange(currentPage + 1)}
          className="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-white/5 border rounded-md disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>

      {/* Desktop */}
      <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <p className="text-sm text-gray-700 dark:text-gray-300">
          Mostrando <span className="font-medium">{start}</span> a{" "}
          <span className="font-medium">{end}</span> de{" "}
          <span className="font-medium">{totalItems}</span> resultados
        </p>

        <nav className="isolate inline-flex -space-x-px rounded-md" aria-label="Pagination">
          {/* Prev */}
          <button
            disabled={currentPage === 1}
            onClick={() => onPageChange(currentPage - 1)}
            className="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40"
          >
            <span className="sr-only">Anterior</span>
            <svg className="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fillRule="evenodd"
                d="M11.78 14.78a.75.75 0 01-1.06 0L6.47 10.53a.75.75 0 010-1.06l4.25-4.25a.75.75 0 111.06 1.06L8.06 10l3.72 3.72a.75.75 0 010 1.06z"
                clipRule="evenodd"
              />
            </svg>
          </button>

          {/* Pages */}
          {pages.map((p, i) =>
            p === "..." ? (
              <span
                key={i}
                className="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-500"
              >
                ...
              </span>
            ) : (
              <button
                key={i}
                onClick={() => onPageChange(p as number)}
                className={`relative inline-flex items-center px-4 py-2 text-sm font-semibold border ${
                  p === currentPage
                    ? "z-10 bg-indigo-500 text-white border-indigo-500"
                    : "text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-white/5"
                }`}
              >
                {p}
              </button>
            )
          )}

          {/* Next */}
          <button
            disabled={currentPage === totalPages}
            onClick={() => onPageChange(currentPage + 1)}
            className="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40"
          >
            <span className="sr-only">Siguiente</span>
            <svg className="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fillRule="evenodd"
                d="M8.22 5.22a.75.75 0 011.06 0L13.53 9.47a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 11-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 010-1.06z"
                clipRule="evenodd"
              />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  );
};

export default Pagination;