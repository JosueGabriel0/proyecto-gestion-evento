import { useEffect, useState } from "react";
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from "../ui/table";

import Loading from "../loaders/Loading";
import Loading2 from "../loaders/Loading2";
import EditButton from "../actions/EditButton";
import DeleteButton from "../actions/DeleteButton";
import Pagination from "../common/Pagination";

interface Column<T> {
  key: keyof T | string;
  label: string;
  render?: (item: T) => React.ReactNode; // custom render
}

interface PaginatedResponse<T> {
  data: T[];
  total: number;
  per_page: number;
  current_page: number;
}

interface BasicTableOneProps<T> {
  columns: Column<T>[];
  fetchData: (page: number, perPage: number, search?: string) => Promise<PaginatedResponse<T>>;
  searchTerm: string;
  onEdit?: (item: T) => void;
  onDelete?: (item: T) => void;
  refreshTrigger?: number; // 👈 NUEVA PROP
}

export default function BasicTableOne<T>({
  columns,
  fetchData,
  searchTerm,
  onEdit,
  onDelete,
  refreshTrigger
}: BasicTableOneProps<T>) {
  const [data, setData] = useState<T[]>([]);
  const [pagination, setPagination] = useState({
    current_page: 1,
    per_page: 10,
    total: 0,
  });
  const [loading, setLoading] = useState(true);
  const [isDark, setIsDark] = useState(false);

  const load = async (page = 1, perPage = 10, term = "") => {
    setLoading(true);
    try {
      const response = await fetchData(page, perPage, term);
      setData(response.data);
      setPagination({
        current_page: response.current_page,
        per_page: response.per_page,
        total: response.total,
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    // Detecta dark mode
    const observer = new MutationObserver(() => {
      setIsDark(document.documentElement.classList.contains("dark"));
    });
    observer.observe(document.documentElement, { attributes: true });
    setIsDark(document.documentElement.classList.contains("dark"));
    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    const delayDebounce = setTimeout(() => {
      load(1, pagination.per_page, searchTerm);
    }, 400);
    return () => clearTimeout(delayDebounce);
  }, [searchTerm]);

  useEffect(() => {
    if (!loading) {
      load(pagination.current_page, pagination.per_page, searchTerm);
    }
  }, [refreshTrigger]);

  if (loading) {
    return (
      <div className="flex justify-center pt-20">
        {isDark ? <Loading2 /> : <Loading />}
      </div>
    );
  }

  const totalPages = Math.ceil(pagination.total / pagination.per_page);

  return (
    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
      <div className="w-239 overflow-x-auto">
        <Table>
          <TableHeader>
            <TableRow>
              {columns.map((col) => (
                <TableCell
                  key={col.key as string}
                  isHeader
                  className="px-5 py-3 font-medium text-gray-500 dark:text-gray-400"
                >
                  {col.label}
                </TableCell>
              ))}
              {(onEdit || onDelete) && (
                <TableCell
                  isHeader
                  className="px-5 py-3 font-medium text-gray-500 dark:text-gray-400 text-center w-32"
                >
                  Acciones
                </TableCell>
              )}
            </TableRow>
          </TableHeader>
          <TableBody>
            {data.map((item, idx) => (
              <TableRow key={idx}>
                {columns.map((col) => (
                  <TableCell key={col.key as string} className="px-5 py-4 text-gray-900 dark:text-white">
                    {col.render ? col.render(item) : (item as any)[col.key]}
                  </TableCell>
                ))}
                {(onEdit || onDelete) && (
                  <TableCell className="px-5 py-4 flex justify-center gap-3 w-50">
                    {onEdit && <EditButton onClick={() => onEdit(item)} />}
                    {onDelete && <DeleteButton onClick={() => onDelete(item)} />}
                  </TableCell>
                )}
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>

      <Pagination
        currentPage={pagination.current_page}
        totalPages={totalPages}
        totalItems={pagination.total}
        perPage={pagination.per_page}
        onPageChange={(page) => load(page, pagination.per_page, searchTerm)}
      />
    </div>
  );
}