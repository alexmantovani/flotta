<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand = ['Mercedes', 'BMW', 'Audi', 'Volkswagen', 'Ford', 'Fiat', 'Renault', 'Seat', 'Peugeot', 'Citroen'];
        $model = ['Clio', 'Astra', 'Focus', 'Ibiza', 'Leon', 'Civic', 'Corolla', 'Yaris', 'Cayenne', 'Macan'];
        return [
            'plate' => strtoupper($this->faker->unique()->bothify('??###??')),
            'model' => $model[$this->faker->numberBetween(0, 9)],
            'brand' => $brand[$this->faker->numberBetween(0, 9)],
            'logo' => ' <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 520 520.1" xml:space="preserve"
                                width="38px" height="38px">
                                <g id="_x33_P2o3m_1_">
                                    <g>
                                        <path
                                            d="M3,271.7c0-7.5,0-14.9,0-22.4c0.2-1.5,0.5-2.9,0.7-4.4c1.7-12.5,2.4-25.3,5.1-37.6C33.5,92.9,131.9,9.4,248.6,3.9
                                   c63.6-3,121.2,14.5,171.6,53.6c52.9,41,85.2,94.7,96.7,160.8c1.5,8.5,2.4,17.1,3.5,25.7c0,11.1,0,22.1,0,33.2
                                   c-0.3,1.3-0.6,2.6-0.8,3.9c-2.9,16.3-4.5,33-8.9,48.7C476.4,450.8,361,528.6,235,515.7c-60.4-6.1-112.5-30.9-155.6-73.5
                                   c-41.3-40.8-65.9-90.3-73.8-147.9C4.6,286.8,3.8,279.2,3,271.7z M257.8,33.1c-6.8,0-13.8-0.1-20.6,0c-2.5,0-5.1,0.4-7.6,0.6
                                   c-50.3,4-95.6,21.2-136.3,50.8c-18.9,13.7-34.7,30.2-42.2,52.7c-4.6,13.9-7.4,28.6-9.3,43.2c-6.7,51-2.4,101.3,11.7,150.7
                                   c9.8,34.8,25.2,67,46.4,96.3c17,23.3,40.4,37.4,67,46.7c59.1,20.5,119,21.6,178.8,4.4c40.4-11.6,73.5-32.6,93.5-72.5
                                   c27.1-54,43-110.1,43.5-170.7c0.2-23.9-1.8-47.8-5.6-71.4c-5.8-35.6-22.8-64.2-53.6-83.7C373.1,48.2,317.7,32.8,257.8,33.1z" />
                                        <path d="M81.2,189.2c0.8-18.5,0.7-35.8,2.7-52.8c2.3-20,13.6-34.3,32.6-42.1C141.6,84,168,78.3,194.8,74.6
                                   c29.2-4.1,58.6-5.6,88.1-4.6c35.7,1.2,70.9,5.4,104.6,17.9c7.3,2.7,14.3,5.9,21.3,9.4c18.4,9.2,26.9,25.4,29.9,44.8
                                   c1.7,10.6,1.8,21.4,2.1,32.1c1.8,71.4-8.6,141.1-30.6,209.1c-4.4,13.4-9.2,26.4-14.1,39.6c-0.7,1.9-2,3.6-3.5,5
                                   c-14.2,12.9-31.5,19.7-49.7,24.7c-33.1,9-66.9,10.6-100.9,8.5c-25.4-1.5-50.5-5.3-74.5-13.9c-5.7-2.1-11.4-4.6-16.9-7.3
                                   c-11.1-5.4-18.8-13.6-23.7-25.4c-23.6-56.1-38.6-114.4-43.1-175.2C82.4,222.3,82,205.1,81.2,189.2z M269.9,369.2
                                   c0-33,0-65.6,0-98.3c10.3,0,19.7,0,29.7,0c0,33,0,65.4,0,98.3c7.4,0,14.1,0,21.3,0c0.1-1.5,0.2-2.7,0.2-4c0-53.1,0-106.1,0-159.1
                                   c0-2-0.5-4.1-1.5-6c-5.6-11.7-11.5-23.3-17.2-34.9c-1.2-2.7-2.8-3.6-5.8-3.5c-13.6,0.2-27.2,0.1-40.7,0.1c-5.8,0-5.8,0-5.8,5.8
                                   c0,65.4,0,130.8,0,196.4c0,1.8,0,3.5,0,5.5C256.6,369.2,262.7,369.2,269.9,369.2z M193.2,162.6c-20.7,0-41.3,0-61.6,0
                                   c0,69.3,0,138.2,0,207.3c7.2,0,14,0,21.3,0c0-33.1,0-65.8,0-99.3c11,0,21.5,0,31.8,0c0-8,0-15.4,0-23.3c-10.8,0-21.2,0-31.6,0
                                   c0-20.7,0-41.1,0-61.8c13.5,0,26.6,0,40.1,0C193.2,177.5,193.2,170.2,193.2,162.6z M369.4,185.8c7.5,0,14.4,0,21.6,0
                                   c0-8.1,0-15.7,0-23.2c-21.9,0-43.4,0-64.9,0c0,7.9,0,15.3,0,23.2c7.1,0,13.7,0,21.6,0c0,61.4,0,122.3,0,183.4c7.9,0,14.5,0,21.8,0
                                   C369.4,308.3,369.4,247.3,369.4,185.8z M224.4,369.9c0-69.5,0-138.6,0-207.9c-7.3,0-14.1,0-20.9,0c0,69.5,0,138.6,0,207.9
                                   C210.5,369.9,217.3,369.9,224.4,369.9z" />
                                        <path
                                            d="M299.4,242.5c-10.1,0-19.5,0-29,0c0-19.2,0-38.2,0-57.7c5.5,0,10.9-0.1,16.4,0.1c0.9,0,2.1,1.5,2.5,2.5
                                   c3.2,7.6,6.3,15.1,9.4,22.7c0.3,0.7,0.6,1.5,0.6,2.2C299.4,222.2,299.4,232.3,299.4,242.5z" />
                                    </g>
                                </g>
                            </svg>'
        ];
    }
}
