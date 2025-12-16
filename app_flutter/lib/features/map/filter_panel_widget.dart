/*
* Nombre de la clase         : filter_panel_widget.dart
* Descripción de la clase    : Panel de filtros avanzados para búsqueda de restaurantes,
*                               permite filtrar por calificación, tipo de negocio, rango de precios y estado.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Alan Osvaldo Basilio Delgado
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Maileth Patiño Ensastegui
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

import 'package:flutter/material.dart';
import '../../core/constants/app_colors.dart';

class FilterPanel extends StatefulWidget {
  final Function(Map<String, dynamic>) onFiltersChanged;

  const FilterPanel({super.key, required this.onFiltersChanged});

  @override
  State<FilterPanel> createState() => _FilterPanelState();
}

class _FilterPanelState extends State<FilterPanel> {
  double _minRating = 0;
  String _businessType = 'todos';
  String _priceRange = 'todos';
  bool _isOpenNow = false;

  final Map<String, String> _priceRanges = {
    'todos': 'Todos los precios',
    'economico': 'Económico (\$)',
    'medio': 'Precio Medio (\$\$)',
    'premium': 'Premium (\$\$\$)',
  };

  final Map<String, String> _businessTypes = {
    'todos': 'Todos los tipos',
    'formal': 'Negocio Formal',
    'informal': 'Negocio Informal',
  };

  void _applyFilters() {
    final filters = {
      'minRating': _minRating,
      'businessType': _businessType,
      'priceRange': _priceRange,
      'isOpenNow': _isOpenNow,
    };
    widget.onFiltersChanged(filters);
  }

  void _resetFilters() {
    setState(() {
      _minRating = 0;
      _businessType = 'todos';
      _priceRange = 'todos';
      _isOpenNow = false;
    });
    _applyFilters();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: AppColors.shadow,
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'Filtros de Búsqueda',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textPrimary,
                ),
              ),
              IconButton(
                icon: const Icon(Icons.close, color: AppColors.error),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // Filtro por Calificación Mínima
          _buildRatingFilter(),
          const SizedBox(height: 20),

          // Filtro por Tipo de Negocio
          _buildBusinessTypeFilter(),
          const SizedBox(height: 20),

          // Filtro por Rango de Precios
          _buildPriceRangeFilter(),
          const SizedBox(height: 20),

          // Filtro por Estado (Abierto ahora)
          _buildOpenNowFilter(),
          const SizedBox(height: 24),

          // Botones de acción
          _buildActionButtons(),
        ],
      ),
    );
  }

  Widget _buildRatingFilter() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Calificación Mínima',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 8),
        Row(
          children: [
            const Icon(Icons.star, color: Colors.amber, size: 20),
            const SizedBox(width: 8),
            Text(
              '${_minRating.toInt()}+ estrellas',
              style: const TextStyle(
                color: AppColors.secondary,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
        const SizedBox(height: 8),
        Slider(
          value: _minRating,
          min: 0,
          max: 5,
          divisions: 5,
          label: _minRating == 0 ? 'Cualquier calificación' : '${_minRating.toInt()}+',
          onChanged: (value) {
            setState(() {
              _minRating = value;
            });
          },
          activeColor: AppColors.secondary,
          inactiveColor: AppColors.inactive,
        ),
      ],
    );
  }

  Widget _buildBusinessTypeFilter() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Tipo de Negocio',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: _businessTypes.entries.map((entry) {
            final isSelected = _businessType == entry.key;
            return FilterChip(
              label: Text(entry.value),
              selected: isSelected,
              onSelected: (selected) {
                setState(() {
                  _businessType = selected ? entry.key : 'todos';
                });
              },
              selectedColor: AppColors.secondary,
              checkmarkColor: AppColors.white,
              labelStyle: TextStyle(
                color: isSelected ? AppColors.white : AppColors.textPrimary,
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildPriceRangeFilter() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Rango de Precios',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: _priceRanges.entries.map((entry) {
            final isSelected = _priceRange == entry.key;
            return FilterChip(
              label: Text(entry.value),
              selected: isSelected,
              onSelected: (selected) {
                setState(() {
                  _priceRange = selected ? entry.key : 'todos';
                });
              },
              selectedColor: AppColors.primary,
              checkmarkColor: AppColors.white,
              labelStyle: TextStyle(
                color: isSelected ? AppColors.white : AppColors.textPrimary,
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildOpenNowFilter() {
    return Row(
      children: [
        Switch(
          value: _isOpenNow,
          onChanged: (value) {
            setState(() {
              _isOpenNow = value;
            });
          },
          activeColor: AppColors.secondary,
        ),
        const SizedBox(width: 8),
        const Text(
          'Solo abiertos ahora',
          style: TextStyle(
            color: AppColors.textPrimary,
            fontWeight: FontWeight.w500,
          ),
        ),
      ],
    );
  }

  Widget _buildActionButtons() {
    return Row(
      children: [
        Expanded(
          child: OutlinedButton(
            onPressed: _resetFilters,
            style: OutlinedButton.styleFrom(
              foregroundColor: AppColors.error,
              side: const BorderSide(color: AppColors.error),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
            child: const Text('Limpiar Filtros'),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: ElevatedButton(
            onPressed: () {
              _applyFilters();
              Navigator.pop(context);
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.secondary,
              foregroundColor: AppColors.textOnSecondary,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
            child: const Text('Aplicar Filtros'),
          ),
        ),
      ],
    );
  }
}
